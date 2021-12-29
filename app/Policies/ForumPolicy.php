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
        $student = $forum->students()->where('users.id', $user->id)->first();
        return $student || $forum->owner == $user->id;
    }
    public function update(User $user, Forum $forum)
    {
        // Log::channel('event')->info($user->id == $forum->ownerDetails->id, ['i was here']);
        return $forum->ownerDetails->id == $user->id;
    }
}
