<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referrel extends Model
{
    use HasFactory;
    protected $fillable = [
        'referrel_token',
        'cut_of',
        'item_id',
        'item_type',
        'quantity',
        'expires_at'
    ];

}
