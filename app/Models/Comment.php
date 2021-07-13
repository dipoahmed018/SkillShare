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
        'commentable_type',

    ];
    public function owner_details()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function reply()
    {
        return $this->hasMany(Comment::class, 'commentable_id')->where('commentable_type', '=', 'reply');
    }
    public function parent()
    {
        if ($this->commentable_type == 'reply') {
            return $this->belongsTo(Comment::class, 'commentable_id');
        } else {
            return $this->belongsTo(Post::class, 'commentable_id');
        }
    }
    public function allvote()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }
    public function voted($id)
    {
        return $this->allvote()->where('voter_id', '=', $id)->first();
    }
}
