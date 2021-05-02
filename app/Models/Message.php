<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'owner',
        'receiver_id',
        'receiver_type',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
}
