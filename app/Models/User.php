<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\UserRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
    public function tuitions()
    {
        return $this->belongsToMany(Tuition::class, 'tuition_students', 'student_id', 'tuition_id');
    }
    public function Comments()
    {
        return $this->hasMany(Comment::class, 'owner');
    }
    public function myMessage()
    {
        return $this->hasMany(Message::class, 'owner');
    }
    public function hisMessage()
    {
        return $this->morphMany(Message::class, 'receiver', 'receiver_type', 'receiver_id');
    }
    public function tuitionForum()
    {
        return $this->belongsToMany(Forum::class, 'tuition_students', 'student_id', 'tuition_id');
    }
    public function courseForum()
    {
        return $this->belongsToMany(Forum::class, 'course_students', 'student_id', 'tuition_id');
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_member', 'member_id', 'group_id')->withPivot('member_type')->withTimestamps();
    }
    public function requestsFrom()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'my_id')->wherePivot('friend_type', '=', 'request');
    }
    public function requestTo()
    {
        return $this->belongsToMany(User::class, 'friends', 'my_id', 'friend_id')->wherePivot('friend_type', 'request');
    }
    public function blocks()
    {
        return $this->belongsToMany(User::class, 'friends', 'my_id', 'friend_id')->wherePivot('friend_type', '=', 'blocked');
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'my_id', 'friend_id')->wherePivot('friend_type', '=', 'friend');
    }
    public function profile_details()
    {
        return $this->morphOne(FileLink::class, 'fileable','fileable_type','fileable_id')->where('file_type','=','profile_photo');
    }

    public function getAllBlocks()
    {
        $blocks1 = $this->belongsToMany(User::class, 'friends', 'my_id', 'friend_id')->wherePivot('friend_type', '=', 'blocked')->get();
        $blocks2 = $this->belongsToMany(User::class, 'friends', 'friend_id', 'my_id')->wherePivot('friend_type', '=', 'blocked')->get();
        collect($blocks1);
        collect($blocks2);
        return $blocks1->concat($blocks2);
    }

    public function getAllFriends()
    {
        $myfriends1 = $this->belongsToMany(User::class, 'friends', 'my_id', 'friend_id')->wherePivot('friend_type', '=', 'friend')->get();
        $myfriends2 = $this->belongsToMany(User::class, 'friends', 'friend_id', 'my_id')->wherePivot('friend_type', '=', 'friend')->get();
        collect($myfriends1);
        collect($myfriends2);
        return $myfriends1->concat($myfriends2);
    }
    public function getProfilePicture()
    {
        $profile_picture = DB::table('file_link')->whereRaw('file_type = ? AND fileable_id = ? AND fileable_type = ?',['profile_photo',$this->id,'user'])->first();
        return !$profile_picture ? asset('/storage/profile/profile_photo/default.JPG') : $profile_picture->file_link;
    }
    
}
