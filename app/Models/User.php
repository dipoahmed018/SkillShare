<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\UserRelationships\UserRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, UserRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */# code...
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birth_date',
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
        return $this->belongsToMany(Course::class,'course_students','student_id','course_id');
    }
    public function titions()
    {
        return $this->belongsToMany(Tuition::class,'tuition_students','user_id','tuition_id');
    }
}
