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
    public function ownerDetails()
    {
        return $this->belongsTo(User::class, 'owner','id');
    }
    public function forumable()
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
    public function students()
    {
        return $this->belongsToMany(User::class,'course_students','course_id','student_id','forumable_id');
    }
}
