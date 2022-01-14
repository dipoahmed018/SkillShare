<?php

namespace App\Models;

use App\Notifications\NewTutorialAdded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TutorialDetails extends Model
{
    use HasFactory;

    protected $table = 'tutorial_details';
    protected $fillable = [
        'tutorial_id',
        'order',
        'title',
    ];

    //event observers
    protected static function booted()
    {
        static::created(function($tutorialDetails)
        {
            $tutorialDetails->tutorial_video;
            $tutorialDetails->course;
            $students = $tutorialDetails->course->students;
            
            Notification::send($students, new NewTutorialAdded($tutorialDetails));
        });
    }

    //relations
    public function course()
    {
        return $this->hasOneThrough(Course::class, FileLink::class,'id', 'id', 'tutorial_id', 'fileable_id');
    }

    public function tutorial_video()
    {
        return $this->belongsTo(FileLink::class, 'tutorial_id');
    }
}
