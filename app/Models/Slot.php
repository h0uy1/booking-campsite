<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['tent_number', 'tent_id'];

    public function tent()
    {
        return $this->belongsTo(Tent::class);
    }

}
