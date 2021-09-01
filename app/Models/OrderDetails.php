<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    protected $fillable = [
        'payment_intent',
        'client_sc',
        'status',
        'productable_id',
        'productable_type',
        'owner',
    ];

    public function course()
    {
        return $this->morphTo('productable');
    }

}
