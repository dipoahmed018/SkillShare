<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Forum;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class ForumPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function access(User $user, Forum $forum)
    {
        $parent = $forum->forum_type;
        $students = $parent->students->where('id','=',$user->id)->pluck('id');
        return $students->count() > 0 || $forum->owner == $user->id;
    }
    public function update(User $user, Forum $forum)
    {
        // Log::channel('event')->info($user->id == $forum->owner_details->id, ['i was here']);
        return $forum->owner_details->id == $user->id;
    }
}
