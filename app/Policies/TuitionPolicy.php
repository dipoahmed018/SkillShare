<?php

namespace App\Policies;

use App\Models\Tuition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TuitionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function update(User $user, Tuition $tuition)
    {
        return $user->id == $tuition->ownerDetails->id;
    }
    public function watch(User $user, Tuition $tuition)
    {
        $tuition->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0;
    }
    public function delete(User $user, Tuition $tuition)
    {
        return ($tuition->students->count() < 1 && $user->id == $tuition->ownerDetails->id);
    }
    public function review(User $user, Tuition $tuition)
    {
        return $tuition->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0 && $tuition->review()->where('owner', '=', $user->id)->count() < 1 && $user->id !== $tuition->ownerDetails;
    }
}
