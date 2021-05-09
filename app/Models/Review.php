<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'review';
    protected $fillable = [
        'content',
        'stars',
        'owner',
        'reviewable_type',
        'reviewable_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function review_parent()
    {
        return $this->morphTo('reviewable');
    }
    public function review_replys()
    {
        return $this->morphMany(Review::class,'reviewable','reviewable_type','reviewable_id');
    }
}
