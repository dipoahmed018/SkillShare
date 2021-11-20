<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $fillable = [
        'content',
        'owner',
        'vote',
        'commentable_id',
        'comment_type',

    ];
    public function ownerDetails()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function reply()
    {
        return $this->hasMany(Comment::class, 'commentable_id')->where('comment_type', '=', 'reply');
    }
    public function parent()
    {
        if ($this->comment_type == 'reply') {
            return $this->belongsTo(Comment::class, 'commentable_id');
        } else {
            return $this->belongsTo(Post::class, 'commentable_id');
        }
    }
    public function allVotes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
    public function voted($id)
    {
        return $this->allVotes()->where('voter_id', '=', $id)->first();
    }
    public function getPost()
    {
        if ($this->comment_type == 'reply') {
            return Comment::with('parent')->where('id',$this->commentable_id)->get()->pluck('parent')->first();
        } else {
            return Post::find($this->commentable_id);
        }
    }
    public function getForum()
    {
        if ($this->commentable_type == 'reply') {
            return Comment::with('parent.forum')->where('id',$this->commentable_id)->get()->pluck('parent')->pluck('forum')->first();
        } else {
            return Post::with('forum')->where('id',$this->commentable_id)->get()->pluck('forum')->first();
        }
    }
}
