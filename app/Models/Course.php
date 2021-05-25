<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
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
        return $this->morphOne(Forum::class,'forumable');
    }
    public function students()
    {
        return $this->belongsToMany(User::class , 'course_students', 'course_id','student_id')->withPivot(['expired','expires_at'])->withTimestamps();
    }
    public function referrels()
    {
        return $this->morphMany(Referrel::class,'item','item_type','item_id');
    }
    public function catagory()
    {
        return $this->morphToMany(Catagory::class,'catagoryable','catagoryable','catagoryable_id','catagory_id');
    }
    public function review()
    {
        return $this->morphMany(Review::class, 'reviewable','reviewable_type','reviewable_id');
    }
    public function thumblin()
    {
        return $this->morphOne(FileLink::class, 'fileable','fileable_type','fileable_id')->where('file_type','=','thumblin');
    }
}
