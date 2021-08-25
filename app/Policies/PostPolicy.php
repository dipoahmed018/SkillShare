<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class PostPolicy
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

    public function update(User $user, Post $post)
    {
        return $user->id == $post->owner;
    }
    public function access(User $user, Post $post)
    {
        $forum = $post->getForum();
        $student = $forum->members()->where('student_id',$user->id)->first();
        return $forum->owner == $user->id || $student ? true : false;
    }
}
