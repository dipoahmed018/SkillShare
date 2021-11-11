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
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Course\SetThumbnail;
use App\Http\Requests\Course\createCourse;
use App\Http\Requests\Course\DeleteCourse;
use App\Http\Requests\Course\UpdateDetails;
use App\Http\Requests\Course\SetIntroduction;
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
            return response()->json(['data' => $data], 200);
        }
        $builder = Course::query()->with([
            'thumbnail',
            'ownerDetails',
        ]);

        $builder->Price($request->min_price?? 5, $request->max_price ?? 10000);

        //catagory filter
        if ($request->catagory && $request->catagory !== 'default') {
            $builder->Catagory($request->catagory);
        }

        $builder->AvarageRating($request->review ?? 1);
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


    public function setThumbnail(setThumbnail $request, Course $course)
    {
        $user = $request->user();
        if ($user->cannot('update', $course)) {
            return back()->withErrors(['auth' => 'you are not the owner of this course']);
        };
        if ($course->thumbnail) {
            $course->thumbnail->delete();
        }
        $thumbnail = $request->file('thumbnail');
        $file_name = (string) Str::uuid() . time() . '.' . $thumbnail->getClientOriginalExtension();
        $image = Image::make($thumbnail->getRealPath());

        $image->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/public/course/thumbnail/' . $file_name));
        $new_thumbnail = FileLink::create([
            'file_name' => $file_name,
            'file_link' => asset('/storage/course/thumbnail/' . $file_name),
            'file_type' => 'thumbnail',
            'fileable_id' => $course->id,
        ]);
        if ($request->acceptsJson()) {
            return response()->json(['data' => $new_thumbnail, 'success' => true, 'error' => false]);
        }
        return redirect()->back();
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
        $user = request()->user();
        $course->load([
            'thumbnail',
            'ownerDetails',
            'introduction',
            'tutorialDetails',
          //  'review.ownerDetails',
        ])
        ->loadAvg('review as avg_rate', 'stars');
        // ->loadCount('review')

        $course->reviews = $course->review()
            ->with('ownerDetails')
            ->withCount('reviewReplies as repliesCount')
            ->paginate(5, ['*'], 'reviews');

        //permissions
        $course->isStudent =  $user?->courses()->wherePivot('course_id', $course->id)->first() ? true : false;
        $course->isPurchasable = !$course->isStudent && $course->owner !== $user?->id ? true : false;

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


    public function deleteCourse(DeleteCourse $request, Course $course)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $course->forum ? $course->forum->delete() : null;
        $course->introduction ? $course->introduction->delete() : null;
        $course->thumbnail ? $course->introduction->delete() : null;
        $course->referrels ? $course->referrels()->delete() : null;
        //tutorials delete
        $tutorials = $course->tutorial_files;
        if ($tutorials) {
            $tutorial_ids = $course->tutorialDetails->pluck('id');
            foreach ($tutorials as $key => $value) {
                Storage::delete($value->file_link);
            }
            TutorialDetails::query()->whereIn('id', $tutorial_ids)->delete();
            $course->tutorial_files()->delete();
        }
        $course->delete();
        return redirect('/');
    }
}
