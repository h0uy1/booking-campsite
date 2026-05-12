<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tent_number', 'tent_id', 'is_paused','deleted_at'];

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
