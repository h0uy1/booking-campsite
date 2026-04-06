<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['tent_number', 'tent_id', 'is_paused'];

    public function tent()
    {
        return $this->belongsTo(Tent::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function pauses()
    {
        return $this->hasMany(SlotPause::class);
    }
}
