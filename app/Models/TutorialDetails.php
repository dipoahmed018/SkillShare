<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorialDetails extends Model
{
    use HasFactory;
    protected $table = 'tutorial_details';
    protected $fillable = [
        'tutorial_id',
        'title',
    ];
}
