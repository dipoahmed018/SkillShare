<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    protected $table = 'group';

    protected $fillable = [
        'name',
        'owner',
    ];
    public function messages()
    {
        return $this->morphMany(Message::class, 'receiver','receiver_type','receiver_id');
    }
}
