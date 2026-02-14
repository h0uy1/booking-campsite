<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pricing_type', 'max_capacity', 'image','min_capacity'];

    public function prices(){
        return $this->hasMany(Price::class);
    }

    public function images()
    {
        return $this->hasMany(TentImage::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
