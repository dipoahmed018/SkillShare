<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;

    protected $table = 'post';

    protected $fillable = [
        'title',
        'content',
        'owner',
        'postable_id',
        'post_type',
        'answer'
    ];

    public function ownerDetails()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function parent()
    {
        if ($this->post_type == 'answer') {
            return $this->belongsTo(Post::class, 'postable_id');
        }
        return $this->belongsTo(Forum::class, 'postable_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'commentable_id')->where('comment_type', '=', 'parent');
    }

    public function acceptedAnswer()
    {
        return $this->hasOne(Post::class, 'answer');
    }
    public function answers()
    {
        return $this->hasMany(Post::class, 'postable_id')->where('post_type', '=', 'answer');
    }

    public function catagory()
    {
        return $this->morphedByMany(Catagory::class, 'catagoryable', 'catagoryable', 'catagoryable_id', 'catagory_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function voted()
    {
        return $this->hasOne(Vote::class, 'id', 'voteable_id');
    }
    
    public function images()
    {
        return $this->morphMany(FileLink::class, 'fileable');
    }

    public function getForum()
    {
        if ($this->post_type == 'answer') {
            return Post::with('parent')->where('id', $this->postable_id)->get()->pluck('parent')->first();
        } else {
            return Forum::find($this->postable_id);
        }
    }

    public function getVoteCountAttribute()
    {
        $increments = $this->votes()->where('vote_type', 'increment')->count();
        $decrements = $this->votes()->where('vote_type', 'decrement')->count();
        return $increments - $decrements;
    }

    // public function answerdByMe()
    // {
    //     if ($this->post_type == 'answer') {
    //         $postable = Post::find($this->postable_id);
    //         return $postable->answer == $this->id;
    //     }
    //     return false;
    // }
}
