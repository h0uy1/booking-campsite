<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    public function user(){

        $slots = Slot::with('tent')->get();
        return view('user.index', compact('slots'));
    }


}
