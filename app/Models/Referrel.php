<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Tuition;

class Referrel extends Model
{
    use HasFactory;
    protected $table = 'referrel';
    protected $fillable = [
        'referrel_token',
        'cut_of',
        'item_id',
        'item_type',
        'quantity',
        'expires_at'
    ];
    public function parent()
    {
        return $this->morphTo('item','item_type','item_id');
    }

}
