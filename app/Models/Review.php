<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

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
    public function replys()
    {
        return $this->hasMany(Review::class, 'reviewable_id')->where('reviewable_type','=','reply');
    }
    public function parent()
    {
        return $this->belongsTo(Review::class, 'reviewable_id')->where('reviewable_type','=','parent');
    }
}
