<?php

namespace App\Http\Controllers\Course;

use App\Models\User;
use App\Models\Forum;
use App\Models\Course;
use App\Models\FileLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TutorialDetails;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Course\AddVideo;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Course\DeleteVideo;
use App\Http\Requests\Course\SetThumblin;
use App\Http\Requests\Course\createCourse;
use App\Http\Requests\Course\DeleteCourse;
use App\Http\Requests\Course\UpdateDetails;
use App\Http\Requests\Course\SetIntroduction;
use App\Services\VideoStream;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // return $request->all();
        if ($request->has('search')) {
            $data = Course::search($request->search)->get();
            if ($request->suggestion) {
                return response()->json(['data' => $data->map->only('title', 'id'), 'success' => 'true']);
            }
            return $data;
        }
        $builder = Course::query()->selectRaw('AVG(review.stars) AS avg_rate, course.*')
        ->with(['thumblin' => fn($q) => $q->select('file_link.*'), 'owner_details' => fn($q) => $q->select('users.*')]);
        // price filter
        if ($request->min_price && $request->max_price) {
            $builder->Price($request->min_price, $request->max_price);
        }
        //catagory filter
        if ($request->catagory && $request->catagory !== 'default') {
            $builder->Catagory($request->catagory);
        }

        $builder->Review($request->review ?: 10);
        // $builder->where('course.id', '=', '6');
        //order and paginate
        $builder->orderBy($request->order_by ?: 'price', 'asc');
        $data = $builder->paginate($request->per_page ?: 5, ['*'], 'course_page');

        return view('pages.course.index', ['data' => $data]);
    }
    public function createCourse(createCourse $request)
    {
        $user = Auth::user();
        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'owner' => $user->id,
        ]);
        $forum = Forum::create([
            'name' => $request->forum_name,
            'description' => $request->forum_description,
            'forumable_id' => $course->id,
            'forumable_type' => 'course',
            'owner' => $course->owner,
        ]);
        $course->forum_id = $forum->id;
        $course->save();
        return redirect()->back()->with('course', $course);
    }
    public function setThumblin(SetThumblin $request, Course $course)
    {

        $user = $request->user();
        if ($user->cannot('update', $course)) {
            return 'hello';
            return back()->withErrors(['auth' => 'you are not the owner of this course']);
        };
        if ($course->thumblin) {
            $course->thumblin->delete();
        }
        $thumblin = $request->file('thumblin');
        $file_name = (string) Str::uuid() . time() . '.' . $thumblin->getClientOriginalExtension();
        $image = Image::make($thumblin->getRealPath());

        $image->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/public/course/thumblin/' . $file_name));
        FileLink::create([
            'file_name' => $file_name,
            'file_link' => asset('/storage/course/thumblin/' . $file_name),
            'file_type' => 'thumblin',
            'fileable_id' => $course->id,
        ]);
        return redirect('/show/course/' . $course->id);
    }
    public function setIntroduction(SetIntroduction $request, Course $course)
    {
        $data = $request->chunk_file ? blobConvert($request->chunk_file) : null;
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->introduction_name) . $course->id;
        $directory = '/introduction//' . $directory_name;
        if ($request->header('x-cancel')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ?  $chunk->message : 'something went wrong';
        }
        if ($request->header('x-resumeable')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ? $chunk->file_name : abort($chunk->status, $chunk->message);
        }

        //upload 
        if ($request->header('x-last') == true) {
            $chunk = chunkUpload($directory, $data, 'public/course/introduction/');
            if ($chunk->status !== 200) {
                return abort($chunk->status, $chunk->message);
            }
            if ($file = $course->introduction) {
                $file_path = strstr($file->file_link, '/' . $file->fileable_type);
                Storage::disk('public')->delete($file_path);
                $file->delete();
            }

            $file = FileLink::create([
                'file_name' => $chunk->file_name,
                'file_link' => asset('/storage/course/introduction/' . $chunk->file_name),
                'file_type' => 'introduction',
                'fileable_id' => $course->id,
                'fileable_type' => 'course',
            ]);
            return $file;
        }

        $chunk = chunkUpload($directory, $data);
        if ($chunk->status == 200) {
            return $chunk->file_name;
        }
    }
    public function showDetails(Course $course)
    {
        $course->thumblin;
        $course->introduction = $course->introduction ? $course->introduction->file_link : null;
        $course->tutorials = $course->get_tutorials_details();
        return view('pages/course/Show', ['course' => $course]);
    }

    public function updateDetails(UpdateDetails $request, Course $course)
    {
        Log::channel('event')->info('update-detais', [$request->all()]);
        if ($request->title) {
            $course->title = $request->title;
            $course->save();
        }
        if ($request->description) {
            $course->description = $request->description;
            $course->save();
        }
        if ($request->price) {
            $course->price = $request->price;
            $course->save();
        }
        return redirect('/show/course/' . $course->id);
    }
    public function addTutorial(AddVideo $request, Course $course)
    {

        $course_tutorials = collect($course->get_tutorials_details());
        $data = $request->chunk_file ? blobConvert($request->chunk_file) : null;
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->tutorial_name) . $course->id;
        $title = 'please provide your tutorial title';
        $directory = '/' . $directory_name;

        if ($request->header('x-cancel')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ?  $chunk->message : 'something went wrong';
        }
        if ($request->header('x-resumeable')) {
            $chunk = chunkUpload($directory, 'no data');
            return $chunk->status == 200 ? $chunk->file_name : abort($chunk->status, $chunk->message);
        }


        if ($request->header('x-last') == true) {
            //chunk upload
            $chunk = chunkUpload($directory, $data, 'private/course/tutorial/');
            if ($chunk->status == 422) {
                return abort(422, $chunk->message);
            }


            $file = FileLink::create([
                'file_name' => $chunk->file_name,
                'file_link' => 'private/course/tutorial/' . $chunk->file_name,
                'file_type' => 'tutorial',
                'security' => 'private',
                'fileable_type' => 'course',
                'fileable_id' => $course->id,
            ]);
            $tutorial_details = TutorialDetails::create([
                'tutorial_id' => $file->id,
                'title' => $title,
                'order' => $course_tutorials->count() + 1,
            ]);
            return $tutorial_details;
        }

        $chunk = chunkUpload($directory, $data);

        return $chunk->status == 200 ? response($chunk->file_name, 200) : abort(422, $chunk->message);
    }

    public function attachCatagory(Request $request, Course $course)
    {
        $rules = [
            'catagory' => 'required|integer'
        ];
        $request->validate($rules, $request->all());
        if ($request->user()->cannot('update', $course)) {
            return abort(401, 'you are not authorized');
        };
        return $course->catagory()->syncWithoutDetaching($request->catagory);
    }
    public function detachCatagory(Request $request, Course $course)
    {
        $rules = [
            'catagory' => 'required|integer'
        ];
        $request->validate($rules, $request->all());
        if ($request->user()->cannot('update', $course)) {
            return abort(401, 'you are not authorized');
        };
        return $course->catagory()->detach($request->catagory);
    }
    public function setTutorialDetails(UpdateDetails $request, Course $course, TutorialDetails $tutorial)
    {
        if (!$request->title && ($request->position == $tutorial->order || !$request->position)) {
            return back()->withErrors(['invalid' => 'Provided data is invalid']);
        }
        // save title
        $tutorial->title = $request->title;
        $tutorial->save();
        //positioning
        //going up
        if ($request->position < $tutorial->order) {
            $all_tutorials = $course->get_tutorials_details()->whereBetween('order', [$request->position, $tutorial->order - 1])->pluck('id');
            TutorialDetails::query()->whereIn('id', $all_tutorials)->increment('order', 1);
            $tutorial->order = $request->position;
            $tutorial->save();
            return redirect('/show/course/' . $course->id);
        }
        //going down
        if ($request->position > $tutorial->order) {
            $all_tutorials = $course->get_tutorials_details();
            $last_order = $all_tutorials->max('order');
            $in_between = $all_tutorials->whereBetween('order', [$tutorial->order + 1, $request->position])->pluck('id');
            TutorialDetails::query()->whereIn('id', $in_between)->decrement('order', 1);
            $tutorial->order = $request->position > $last_order ? $last_order : $request->position;
            $tutorial->save();
            return redirect('/show/course/' . $course->id);
        }
        return redirect('/show/course/' . $course->id);
    }
    public function showTutorialEdit(Request $request, Course $course, TutorialDetails $tutorial)
    {
        if ($request->user()->cannot('update', $course)) {
            return abort(401, 'you are not authorized to edit this tutorial');
        }
        $course->tutorials = collect($course->get_tutorials_details());
        $course->catagory;
        return view('pages/course/EditTutorial', ['tutorial' => $tutorial, 'course' => $course]);
    }
    public function deleteVideo(DeleteVideo $request, Course $course, TutorialDetails $tutorial)
    {
        try {
            $file = $tutorial->tutorial_video;
            $all_tutorials = $course->get_tutorials_details()->where('order', '>', $tutorial->order)->pluck('id');
            Log::channel('event')->info('outside conut', [$all_tutorials]);
            if ($all_tutorials->count() > 0) {
                Log::channel('event')->info('inside conut', [$all_tutorials]);
                TutorialDetails::query()->whereIn('id', $all_tutorials)->decrement('order', 1);
            }
            Storage::delete($file->file_link);
            $file->delete();
            $tutorial->delete();
            return redirect('/show/course/' . $course->id);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function deleteCourse(DeleteCourse $request, Course $course)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $course->forum ? $course->forum->delete() : null;
        $course->introduction ? $course->introduction->delete() : null;
        $course->thumblin ? $course->introduction->delete() : null;
        $course->referrels ? $course->referrels()->delete() : null;
        //tutorials delete
        $tutorials = $course->tutorial_files;
        if ($tutorials) {
            $tutorial_ids = $course->get_tutorials_details()->pluck('id');
            foreach ($tutorials as $key => $value) {
                Storage::delete($value->file_link);
            }
            TutorialDetails::query()->whereIn('id', $tutorial_ids)->delete();
            $course->tutorial_files()->delete();
        }
        $course->delete();
        return redirect('/');
    }
    public function streamTutorial(Request $request, TutorialDetails $tutorial, Course $course)
    {
        if ($request->user()->cannot('tutorial', $course) && $request->user()->cannot('update', $course)) {
            return abort(401, 'you are not autorized to access this course tutorial');
        }
        $file_details = FileLink::findOrFail($tutorial->tutorial_id);
        $stream = new VideoStream(storage_path('/app//' . $file_details->file_link));
        $stream->start();
    }
}
