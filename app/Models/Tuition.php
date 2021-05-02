<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;
    protected $table = 'tuition';
    protected $fillable = [
        'stripe_id',
        'title',
        'description',
        'owner',
        'forum_id',
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class,'owner','id');
    }
    public function forum()
    {
        return $this->hasOne(Forum::class, 'forumable_id')->where('forumable_type','=','tution');
    }
    public function students($expired)
    {
        if (!$expired) {
            return $this->belongsToMany(User::class , 'tuition_students', 'tuition_id','user_id')->wherePivot('expired', '=', false);
        }
        return $this->belongsToMany(User::class , 'tuition_students', 'tuition_id','user_id')->wherePivot('expired', '=', true);

    }
    
}
