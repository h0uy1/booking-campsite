<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'slot_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'status',
        'total_price'
    ];
    
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
