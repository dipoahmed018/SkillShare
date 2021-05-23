<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\createCourse;
use App\Models\Course;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    }
    public function setThumblin()
    {
        
    }
    public function setIntruduction()
    {
        
    }
    public function updateDetails()
    {
        
    }
    public function addVideo()
    {
        
    }
    public function deleteVideo()
    {
        
    }
    public function deleteCourse()
    {
        
    }
}
