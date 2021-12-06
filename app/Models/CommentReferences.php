<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReferences extends Model
{
    
    use HasFactory;
    
    protected $table = 'comment_references';
    protected $fillable = [
        'user_id',
        'comment_id',
    ];

    public function comments()
    {
        $this->belongsTo(Comment::class, 'comment_id');
    }
    public function users()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
