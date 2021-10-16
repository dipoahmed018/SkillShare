<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public function ownerDetails()
    {
        return $this->belongsTo(User::class, 'owner');
    }
    public function reviewParent()
    {
        return $this->morphTo('reviewable');
    }
    public function reviewReplies()
    {
        return $this->morphMany(Review::class, 'reviewable', 'reviewable_type', 'reviewable_id');
    }

    public function base_parent()
    {
        $base_parent = $this->reviewParent;

        return $base_parent->reviewable_type ? $base_parent->reviewParent : $base_parent;
    }
}
