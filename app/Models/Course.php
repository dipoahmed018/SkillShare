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

    public function ownerDetails()
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

    public function tutorialDetails()
    {
        return $this->hasManyThrough(TutorialDetails::class, FileLink::class, 'fileable_id', 'tutorial_id')->where('file_link.fileable_type', 'course');
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

    public function is_student($user)
    {
        return $this->students()->wherePivot('student_id', '=', $user->id)->get()->count() > 0;
    }
}
