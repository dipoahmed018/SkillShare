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
        return $user->id === $course->owner_details->id;
    }
    public function delete(User $user, Course $course)
    {
        return ($course->students->count() < 1 && $course->owner == $user->id);
    }
    public function tutorial(User $user, Course $course)
    {
        return $course->is_student($user);
    }
    public function review(User $user, Course $course)
    {
       return $course->students()->wherePivot('student_id','=', $user->id)->get()->count() > 0 && $course->review()->where('owner','=',$user->id)->count() < 1 && $user->id !== $course->owner;
    }
    public function purchase(User $user, Course $course)
    {
        return $course->owner !== $user->id && $user->email_verified_at !== null && !$course->is_student($user);
    }
}
