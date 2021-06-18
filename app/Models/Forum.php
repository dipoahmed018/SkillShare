<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;
    protected $table = 'forum';
    protected $fillable = [
        'name',
        'description',
        'owner',
        'forumable_id',
        'forumable_type',
    ];
    public function owner_details()
    {
        return $this->belongsTo(User::class, 'owner','id');
    }
    public function forum_type()
    {
        return $this->morphTo('forumable');
    }
    public function questions()
    {
        return $this->hasMany(Post::class, 'postable_id')->where('post_type','=','question');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'postable_id')->where('post_type','=','post');
    }
    public function members()
    {
        if ($this->forumable_type == 'course') {
            return $this->belongsToMany(User::class,'course_students','course_id','student_id','forumable_id');
        } else {
            return $this->belongsToMany(User::class,'tuition_students','tuition_id','student_id','forumable_id');
        }
    }
}
