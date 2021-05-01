<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'vote',
        'owner',
        'postable_id',
        'post_type',
        
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function forum()
    {
        return $this->belongsTo(Post::class, 'postable_id');
    }

}
