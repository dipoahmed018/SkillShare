<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Review;
trait UserRelationships {
    
    public function myForums()
    {
        return $this->hasMany(Forum::class, 'owner');
    }
    public function myPosts()
    {
        return $this->hasMany(Post::class,'owner');
    }
    public function myCourses()
    {
        return $this->hasMany(Course::class, 'owner');
    }
    public function myComments()
    {
        return $this->hasMany(Comment::class,'owner');
    }
    public function myReviews()
    {
        return $this->hasMany(Review::class, 'owner');
    }
}