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
    public function thumbnail()
    {
        return $this->morphOne(FileLink::class, 'fileable', 'fileable_type', 'fileable_id')->where('file_type', '=', 'thumbnail');
    }
    public function introduction()
    {
        return $this->morphOne(FileLink::class, 'fileable', 'fileable_type', 'fileable_id')->where('file_type', '=', 'introduction');
    }
    public function tutorial_files()
    {
        return $this->morphMany(FileLink::class, 'fileable', 'fileable_type', 'fileable_id')->where('file_type', '=', 'tutorial');
    }

    //locale scope
    public function scopePrice($query, $from, $to)
    {
        return $query->whereBetween('price', [$from, $to]);
    }

    public function scopeCatagory($query, $catagory)
    {
        return $query->whereHas('catagory', fn ($q) => $q->whereIn('catagory.id', is_array($catagory) ? $catagory : [$catagory]));
    }

    public function scopeReview($query, $review = 10)
    {
        //each time wheile using this scope you have to add { AVG(review.stars) AS avg_rate } to you select statement
        return $query->leftJoin('review', fn ($join) => $join->on('course.id', '=', 'review.reviewable_id')->where('review.reviewable_type', '=', 'course'))
            ->groupBy('course.id')
            ->havingRaw('avg_rate <= ?', [$review])
            ->orHavingRaw('avg_rate IS NULL');
    }

    public function scopeMyCourse($query, $user)
    {
        return $query->join('course_students', fn ($j) => $j->on('course_students.course_id', '=', 'course.id')
            ->where('course_students.id', '=', $user));
    }

    public function scopeMonthlySales($query, $sales = 10)
    {
        //need to add { COUNT(course_students.id) AS sales }
        return $query->join('course_students', 'course.id', 'course_students.course_id')
            ->where('course_students.created_at', '>', now()->subMonth())
            ->groupBy('course.id')
            ->havingRaw('sales >= ?', [$sales]);
    }



    //get all the tutorial details for a specific course
    public function get_tutorials_details()
    {
        $tutorial = DB::table('file_link')->where('file_link.fileable_id', '=', $this->id)->where('file_link.fileable_type', '=', 'course');
        $tutorial_details = DB::table('tutorial_details')
            ->joinSub($tutorial, 'tutorial', 'tutorial_details.tutorial_id', '=', 'tutorial.id')
            ->select('tutorial_details.id', 'tutorial_details.title', 'tutorial_details.order')
            ->orderBy('tutorial_details.order', 'asc')
            ->get();
        return $tutorial_details;
    }

    public function is_student($user)
    {
        return $this->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0;
    }
}
