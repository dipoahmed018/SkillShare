<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;
    protected $table = 'tuition';
    protected $fillable = [
        'stripe_id',
        'title',
        'description',
        'owner',
        'forum_id',
    ];
    
    public function owner_details()
    {
        return $this->belongsTo(User::class,'owner','id');
    }
    public function forum()
    {
        return $this->morphOne(Forum::class,'forumable');
    }
    public function students()
    {
        return $this->belongsToMany(User::class , 'tuition_students', 'tuition_id','student_id')->withPivot(['expired','expires_at'])->withTimestamps();
    }
    public function prices()
    {
        return $this->belongsToMany(Price::class, 'tuition_prices','tuition_id','price_id');
    }
    public function referrels()
    {
        return $this->morphMany(Referrel::class,'item','item_type','item_id');
    }
    public function catagory()
    {
        return $this->morphToMany(Catagory::class,'catagoryable','catagoryable','catagoryable_id','catagory_id');
    }
    public function review()
    {
        return $this->morphMany(Review::class, 'reviewable', 'reviewable_type','reviewable_id');
    }
    
}
