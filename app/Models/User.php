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
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
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
    // public function getProfilePicture()
    // {
    //     $profile_picture = DB::table('file_link')->whereRaw('file_type = ? AND fileable_id = ? AND fileable_type = ?',['profile_photo',$this->id,'user'])->first();
    //     return !$profile_picture ? asset('/storage/profile/profile_photo/default.JPG') : $profile_picture->file_link;
    // }
    public function getProfileLocation()
    {
        return Str::slug($this->name . $this->id);
    }
}
