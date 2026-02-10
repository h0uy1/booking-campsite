<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tent;

class TentImage extends Model
{
    protected $fillable = [
        'tent_id',
        'image_path',
    ];

    public function tent()
    {
        return $this->belongsTo(Tent::class);
    }   
}
