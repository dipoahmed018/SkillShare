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

    public function owner_details()
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
    public function answers()
    {
        return $this->hasMany(Post::class, 'postable_id')->where('post_type', '=', 'answer');
    }
    public function catagory()
    {
        return $this->morphedByMany(Catagory::class, 'catagoryable', 'catagoryable', 'catagoryable_id', 'catagory_id');
    }
    public function allvote()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
    public function images()
    {
        return $this->morphMany(FileLink::class, 'fileable');
    }
    public function voted($id)
    {
        return $this->allvote()->where('voter_id', '=', $id)->first();
    }
    public function voteCount()
    {
        $votes = $this->allvote;
        $increment = $votes->where('vote_type', 'increment')->count();
        $decrement = $votes->where('vote_type', 'decrement')->count();
        return $increment - $decrement;
    }

    public function getForum()
    {
        if ($this->post_type == 'answer') {
            return Post::with('parent')->where('id', $this->postable_id)->get()->pluck('parent')->first();
        } else {
            return Forum::find($this->postable_id);
        }
    }

    public function answerdByMe()
    {
        if ($this->post_type == 'answer') {
            $postable = Post::find($this->postable_id);
            return $postable->answer == $this->id;
        }
        return false;
    }
}
