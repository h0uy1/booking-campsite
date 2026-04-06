<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotPause extends Model
{
    protected $fillable = ['slot_id', 'start_date', 'end_date', 'reason'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
