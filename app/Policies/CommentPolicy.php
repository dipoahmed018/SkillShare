<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Forum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class CommentPolicy
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

    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->owner;
    }
    public function access(User $user, Comment $comment)
    {
        $forum = $comment->getForum();
        $student = $forum->students()->wherePivot("student_id",$user->id)->first();
        return $student || $forum->owner == $user->id ? true : false;
    }
}
