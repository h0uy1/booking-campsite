<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /** @use HasFactory<\Database\Factories\PriceFactory> */
    use HasFactory;
    
    protected $fillable = ['tent_id', 'price_weekday', 'price_weekend', 'capacity', 'adult_price', 'child_price'];

    public function tent() {
        return $this->belongsTo(Tent::class);
    }
}
