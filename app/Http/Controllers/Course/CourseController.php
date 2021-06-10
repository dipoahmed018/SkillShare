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
use Illuminate\Validation\UnauthorizedException;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CourseController extends Controller
{
    public function index()
    {
        return Course::all();
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
        return back()->with('status', 'created')->with('course', $course);
    }
    public function setThumblin(SetThumblin $request, Course $course)
    {

        $user = $request->user();
        if ($user->cannot('update', $course)) {
            return back()->withErrors(['auth' => 'you are not the owner of this course']);
        };
        if ($file = $course->thumblin) {
            $file_path = assetToPath($file->file_link, '/' . $file->fileable_type);
            Storage::disk('public')->delete($file_path);
        }
        $thumblin = $request->file('thumblin');
        $file_name = (string) Str::uuid() . time() . '.' . $thumblin->getClientOriginalExtension();
        $image = Image::make($thumblin->getRealPath());

        $image->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('/app/public/course/thumblin/' . $file_name));
        $file = FileLink::create([
            'file_name' => $file_name,
            'file_link' => asset('/storage/course/thumblin/' . $file_name),
            'file_type' => 'thumblin',
            'fileable_id' => $course->id,
        ]);
        return back()->with('status', 'success')->with('thumblin', $file->file_link);
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
                $file_path = assetToPath($file->file_link, '/' . $file->fileable_type);
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
    public function updateDetails(UpdateDetails $request)
    {
    }
    public function addTutorial(AddVideo $request, Course $course)
    {

        $course_tutorials = collect($course->get_tutorials_details());
        $data = $request->chunk_file ? blobConvert($request->chunk_file) : null;
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->tutorial_name) . $course->id;
        $title ='please provide your tutorial title';
        $directory = '/tutorial//' . $directory_name;

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
            ]);
            return $tutorial_details;
        }

        $chunk = chunkUpload($directory, $data);

        return $chunk->status == 200 ? response($chunk->file_name, 200) : abort(422, $chunk->message);
    }
    public function setTutorialDetails()
    {
        return 'hello world';
    }
    public function showTutorialEdit(Request $request, Course $course, TutorialDetails $tutorial)
    {
        if ($request->user()->cannot('update', $course)) {
            return abort(401, 'you are not authorized to edit this tutorial');
        }
        $course->tutorials = collect($course->get_tutorials_details());
        return view('pages/course/EditTutorial', ['tutorial' => $tutorial, 'course' => $course]);
    }
    public function deleteVideo(DeleteVideo $request, Course $course, TutorialDetails $tutorial)
    {
        try {
            $file = $tutorial->tutorial_video;
            Storage::delete($file->file_link);
            $file->delete();
            $tutorial->delete();
            return redirect('/show/course/' . $course->id);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function deleteCourse(DeleteCourse $request)
    {
    }
}
