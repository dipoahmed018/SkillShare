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
        $student = $post->forum()->with([
            'members' => function ($query) use ($user) {
                $query->where('student_id', $user->id);
            },
            'owner_details' => function ($query) use ($user) {
                $query->where('id', $user->id);
            }
        ])->get();
        if ($student->pluck('members')->first() || $student->pluck('owner_details')->first()) {
            return true;
        }
        return false;
    }
}
