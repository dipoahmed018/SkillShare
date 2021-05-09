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
        return $this->morphedByMany(Post::class,'catagoryable','catagoryable','catagory_id','catagoryable_id');
    }
    public function tuitions()
    {
        return $this->morphedByMany(Tuition::class,'catagoryable','catagoryable','catagory_id','catagoryable_id');
    }
    public function courses()
    {
        return $this->morphedByMany(Course::class,'catagoryable','catagoryable','catagory_id','catagoryable_id');
    }
}
