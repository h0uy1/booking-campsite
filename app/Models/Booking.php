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
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'is_seen',
        'stripe_session_id',
        'expires_at',
        'adults',
        'children',
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
