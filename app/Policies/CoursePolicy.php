<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public $course;
    public function __construct()
    {
    }
    public function update(User $user, Course $course)
    {
        // Log::channel('event')->info('form update',[$course->owner_details]);
        // Log::channel('event')->info('form update',[$user->id]);
        return $user->id === $course->owner_details->id;
    }
    public function delete(User $user, Course $course)
    {
        // Log::channel('event')->info('from delete',[$course->owner_details]);
        // Log::channel('event')->info('from delete',[$user]);
        return ($course->students->count() < 1 && $course->owner_details->id == $user->id);
    }
    public function tutorial(User $user, Course $course)
    {
        return $course->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0;
    }
    public function review(User $user, Course $course)
    {
       return $course->students()->wherePivot('student_id','=', $user->id)->get()->count() > 0 && $course->review()->where('owner','=',$user->id)->count() < 1 && $user->id !== $course->owner_details;
    }
}
