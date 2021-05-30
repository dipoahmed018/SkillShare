<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsTo(User::class, 'owner');
    }
    public function forum()
    {
        return $this->morphOne(Forum::class, 'forumable');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'student_id')->withPivot(['expired', 'expires_at'])->withTimestamps();
    }
    public function referrels()
    {
        return $this->morphMany(Referrel::class, 'item', 'item_type', 'item_id');
    }
    public function catagory()
    {
        return $this->morphToMany(Catagory::class, 'catagoryable', 'catagoryable', 'catagoryable_id', 'catagory_id');
    }
    public function review()
    {
        return $this->morphMany(Review::class, 'reviewable', 'reviewable_type', 'reviewable_id');
    }
    public function thumblin()
    {
        return $this->morphOne(FileLink::class, 'fileable', 'fileable_type', 'fileable_id')->where('file_type', '=', 'thumblin');
    }
    public function introduction()
    {
        return $this->morphOne(FileLink::class, 'fileable', 'fileable_type', 'fileable_id')->where('file_type', '=', 'introduction');
    }
    public function get_tutorials_details()
    {
        $tutorial = DB::table('file_link')->where('file_link.fileable_id', '=', $this->id)->where('file_link.fileable_type','=','course');
        $tutorial_details = DB::table('tutorial_details')
            ->joinSub($tutorial,'tutorial','tutorial_details.tutorial_id','=','tutorial.id')
            ->select('tutorial.id as tutorial_id', 'tutorial_details.title')
            ->get();
        return $tutorial_details;
    }
}
