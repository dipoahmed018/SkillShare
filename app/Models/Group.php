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
    public function owner()
    {
        return $this->belongsTo(User::class,'owner');
    }
    public function messages()
    {
        return $this->morphMany(Message::class, 'receiver','receiver_type','receiver_id');
    }
    public function members()
    {
        return $this->belongsToMany(User::class,'group_member','group_id','member_id')->withPivot('member_type')->withTimestamps();
    }
}
