<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Review $review)
    {
        return $user->id == $review->owner_details->id;
    }
    public function reply(User $user, Review $review)
    {
        return ($user->id == $review->owner_details->id || $review->base_parent()->owner_details->id == $user->id);
    }
    public function delete(User $user, Review $review)
    {
        return $user->id == $review->owner_details->id;
    }
}
