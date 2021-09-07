<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory, Searchable;
    protected $table = 'course';
    protected $fillable = [
        'title',
        'description',
        'price',
        'owner',
        'forum_id',
    ];
    public static $searchable = [
        'title',
        'description',
    ];

    public function owner_details()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function forum()
    {
        return $this->morphOne(Forum::class, 'forumable');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'student_id');
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
    public function tutorial_files()
    {
        return $this->morphMany(FileLink::class,'fileable','fileable_type','fileable_id')->where('file_type','=','tutorial');
    }
    public function get_tutorials_details()
    {
        $tutorial = DB::table('file_link')->where('file_link.fileable_id', '=', $this->id)->where('file_link.fileable_type','=','course');
        $tutorial_details = DB::table('tutorial_details')
            ->joinSub($tutorial,'tutorial','tutorial_details.tutorial_id','=','tutorial.id')
            ->select('tutorial_details.id', 'tutorial_details.title', 'tutorial_details.order')
            ->orderBy('tutorial_details.order','asc')
            ->get();
        return $tutorial_details;
    }

    public function is_student($user)
    {
        return $this->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0;
    }
}
