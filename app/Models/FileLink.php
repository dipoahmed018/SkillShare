<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileLink extends Model
{
    protected $table = 'file_link';
    protected $fillable = [
        'file_name',
        'file_link',
        'file_type',
        'fileable_id',
        'fileable_type',
        'security',
        'created_at',
        'updated_at',

    ];
    protected $hidden = [
        'security',
    ];

    public function owner()
    {
        return $this->morphTo('fileable','fileable_type','fileable_id');
    }
    
}
