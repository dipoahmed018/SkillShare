<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\UserRelationships;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,UserRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ # code...
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birthdate',
        'customer_id',
        'last_four',
        'card_band',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'customer_id',
        'last_four',
        'card_brand',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function myCourses()
    {
        return $this->hasMany(Course::class, 'owner');
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voter_id');
    }
    public function Comments()
    {
        return $this->hasMany(Comment::class, 'owner');
    }
    public function courseForum()
    {
        return $this->belongsToMany(Forum::class, 'course_students', 'student_id', 'course_id');
    }

    public function profilePicture()
    {
        return $this->morphOne(FileLink::class, 'fileable','fileable_type','fileable_id')->where('file_type','=','profile_photo');
    }

    public function getProfilePhotoAttribute()
    {
        return $this->profilePicture?->file_link ?? asset('images/default_cover.jpg');
    }
    public function getProfileLocation()
    {
        return Str::slug($this->name . $this->id);
    }
}
