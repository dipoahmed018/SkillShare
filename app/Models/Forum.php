<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'owner',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner','id');
    }
    public function tuition()
    {
        return $this->hasOne(Tuition::class, 'forumable_id','id')->where('forumable_type','=','tuition');
    }
    public function course()
    {
        return $this->hasOne(Course::class, 'forumable_id','id')->where('forumable_type','=','course');
    }
    public function questions()
    {
        return ;
    }
}
