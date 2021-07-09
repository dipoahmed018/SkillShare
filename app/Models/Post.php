<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'post';
    protected $fillable = [
        'title',
        'content',
        'vote',
        'owner',
        'postable_id',
        'post_type',
        'answer'
    ];

    public function owner_details()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function forum()
    {
        return $this->belongsTo(Forum::class, 'postable_id');
    }
    public function comments()
    {   
        return $this->hasMany(Comment::class, 'commentable_id')->where('commentable_type','=','parent');
    }
    public function answers()
    {   
        return $this->hasMany(Comment::class, 'commentable_id')->where('commentable_type','=','answer');
    }
    public function catagory()
    {
        return $this->morphedByMany(Catagory::class,'catagoryable','catagoryable','catagoryable_id','catagory_id');
    }
    public function vote()
    {
        
    }
}
