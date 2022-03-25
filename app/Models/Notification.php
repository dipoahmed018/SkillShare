<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'content',
        // 'to',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'to');
    }
}
