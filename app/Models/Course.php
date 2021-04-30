<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'price',
        'owner',
        'forum_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,'owner','id');
    }
    public function forum()
    {
        return $this->hasOne(Forum::class,'forumable_id')->where('forumable_type','=','course');
    }
    public function students($expired)
    {
        if (!$expired) {
            return $this->belongsToMany(User::class , 'course_students', 'course_id','student_id');
        }
        return $this->belongsToMany(User::class , 'course_students', 'course_id','student_id');
    }

}
