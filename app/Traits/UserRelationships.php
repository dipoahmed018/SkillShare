<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Group;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tuition;

trait UserRelationships {
    
    public function admin_forum()
    {
        return $this->hasMany(Forum::class, 'owner');
    }
    public function admin_posts()
    {
        return $this->hasMany(Post::class,'owner');
    }
    public function admin_course()
    {
        return $this->hasMany(Course::class, 'owner');
    }
    public function admin_tuition()
    {
        return $this->hasMany(Tuition::class, 'owner');
    }
    public function admin_group()
    {
        return $this->hasMany(Group::class, 'owner');
    }
    public function admin_comments()
    {
        return $this->hasMany(Comment::class,'owner');
    }
    public function admin_review()
    {
        return $this->hasMany(Review::class, 'owner');
    }
    public function admin_notifications()
    {
        return $this->hasMany(Notification::class, 'owner');
    }
}