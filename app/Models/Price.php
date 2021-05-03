<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $table = 'price';
    protected $fillable = [
        'stripe_price',
        'stripe_product',
        'price',
    ];
    public function tuition()
    {
        return $this->belongsToMany(Tuition::class, 'tuition_prices','price_id','tuition_id');
    }
}
