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
        $students = $parent->students->pluck('id');
        Log::channel('event')->info('forum access', [$students]);
    }
    public function edit(User $user, Forum $forum)
    {
        return $forum->owner_details->id == $user->id;
    }
}
