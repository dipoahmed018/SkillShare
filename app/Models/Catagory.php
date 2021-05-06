<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    use HasFactory;
    protected $table = 'catagory';
    protected $fillable = [
        'name'
    ];

    public function posts()
    {
        return $this->morphByMany(Post::class,'catagoryable','catagoryable','catagoryable_id','catagoryable_type');
    }
    public function tuitions()
    {
        return $this->morphedByMany(Tuition::class,'catagoryable','catagoryable','catagoryable_id','catagory_id');
    }
    public function courses()
    {
        return $this->morphedByMany(Course::class,'catagoryable','catagoryable','catagoryable_id','catagory_id');
    }
}
