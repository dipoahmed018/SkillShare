<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $table = 'vote_limiter';
    protected $fillable = [
        'vote_type',
        'voteable_id',
        'voteable_type',
        'voter_id',
    ];

    public function post()
    {
        return $this->morphTo('voteable');
    }
    public function comment()
    {
        return $this->morphTo('voteable');
    }
}
