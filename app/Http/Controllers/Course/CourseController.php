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
use Intervention\Image\Facades\Image;

class CourseController extends Controller
{
    public function index()
    {
        return Course::all();
    }
    public function createCourse(createCourse $request)
    {
        $user = Auth::user()->id;
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
        return back()->with('status', 'created');
    }
    public function setThumblin(SetThumblin $request, Course $course)
    {
        
        $user = $request->user();
        if($user->cannot('update', $course)){
            return back()->withErrors(['auth'=>'you are not the owner of this course']);
        };
        $thumblin = $request->file('thumblin');
        $file_name = (string) Str::uuid() . time() .'.'. $thumblin->getClientOriginalExtension();
        $image = Image::make($thumblin->getRealPath());

        if ($getThumblin = $course->thumblin) {
            return $getThumblin;
        }
        $image->resize(600,null,function($constraint){
            $constraint->aspectRation();
        })->save(storage_path('/app/public/course/thumblin/'.$file_name));
        $file = FileLink::create([
            'file_name' => $file_name,
            'file_link' => asset('/storage/course/thumblin/'. $file_name),
            'file_type' => 'thumblin',
            'fileable_id' => $course->id,
        ]);
        return back()->with('status','success')->with('thumblin', $file->file_link);
    }
    public function setIntrouduction(SetIntroduction $request)
    {
    }
    public function Show_UpdateDetails()
    {
        
    }
    public function updateDetails(UpdateDetails $request)
    {
    }
    public function addVideo(AddVideo $request)
    {
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
