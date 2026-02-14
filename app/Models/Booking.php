<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'slot_id',
        'check_in_date',
        'check_out_date',
        'status'
    ];
    
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
