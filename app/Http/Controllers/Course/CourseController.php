<?php

namespace App\Http\Controllers\Course;

use App\Models\Forum;
use App\Models\Course;
use App\Models\FileLink;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Course\AddVideo;
use App\Http\Requests\Course\DeleteVideo;
use App\Http\Requests\Course\SetThumblin;
use App\Http\Requests\Course\createCourse;
use App\Http\Requests\Course\DeleteCourse;
use App\Http\Requests\Course\UpdateDetails;
use App\Http\Requests\Course\SetIntroduction;
use App\Models\TutorialDetails;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotFoundException;
use Intervention\Image\Facades\Image;
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
        $user = $request->user();
        if ($user->cannot('update', $course)) {
            return new HttpException(401,'You are not allowed to change this introduction video');
        };
        $data = blobConvert($request->chunk_file);
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->tutorial_name) . $course->id;
        $directory = '/introduction//' . $directory_name;
        $file_name = Str::uuid() . '.mp4';

        // delete if exists
        if ($file = $course->introduction) {
            $file_path = assetToPath($file->file_link, '/' . $file->fileable_type);
            Storage::disk('public')->delete($file_path);
            $file->delete();
        }

        // chunk upload
        if ($request->last_chunk) {
            // chunk upload
            chunkUpload($directory, $data, true, 'public/course/introduction/'.$file_name, $request->cancel ? $request->cancel : false);

            $file = FileLink::create([
                'file_name' => $file_name,
                'file_link' => asset('/storage/course/introduction/' . $file_name),
                'file_type' => 'introduction',
                'fileable_id' => $course->id,
                'fileable_type' => 'course',
            ]);
            return $file;
        } else {
            // chunk upload
            chunkUpload($directory, $data, false, null);

            return 'complete';
        }
    }
    public function showDetails(Course $course)
    {
        $course->thumblin;
        $course->introduction = $course->introduction ? $course->introduction->file_link : null;
        $course->owner = User::findOrFail((int) $course->owner);
        $course->tutorials = $course->get_tutorials_details();
        return view('pages/course/Show', ['course' => $course]);
    }
    public function updateDetails(UpdateDetails $request)
    {
    }
    public function addVideo(AddVideo $request, Course $course)
    {
        $user = $request->user();
        if ($user->cannot('update', $course) ) {
            return new HttpException(401, 'you are not allowed to upload tutorial for this course');
        }
        $course_tutorials = collect($course->get_tutorials_details());
        $data = blobConvert($request->chunk_file);
        $directory_name = str_replace([' ', '.', 'mp4', '/'], '', $request->tutorial_name) . $course->id;
        $file_name = Str::uuid() . '.mp4';
        $title = $course_tutorials->count() + 1 . '-please provide your tutorial title';
        $directory = '/tutorial//' . $directory_name;
        if ($request->last_chunk) {
            //chunk upload
            $chunk = chunkUpload($directory, $data, true, 'private/course/tutorial/' . $file_name);
            if ($chunk->status == false) {
                return response($chunk->message,200);
            }
            $file = FileLink::create([
                'file_name' => $file_name,
                'file_link' => 'app/private/course/tutorial/' . $file_name,
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
        } else {
            //chunk upload
            $chunk = chunkUpload($directory, $data, false, false , $request->cancel ? $request->cancel : false);
            if ($chunk->status == false) {
                return response($chunk->message,200);
            }
            return 'complete';
        }
    }
    public function setVideoName()
    {
    }
    public function deleteVideo(DeleteVideo $request)
    {
    }
    public function deleteCourse(DeleteCourse $request)
    {
    }
}
