<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    $stats = [
        'total_tents' => \App\Models\Tent::count(),
        'total_slots' => \App\Models\Slot::count(),
        'recent_tents' => \App\Models\Tent::latest()->take(3)->get(),
        'recent_bookings' => \App\Models\Booking::with('slot.tent')->latest()->take(5)->get(),
    ];
    return view('welcome', compact('stats'));
});
Route::get('/booking', function(){
    return view('booking');
});
Route::get('/all', function(){
    return view('all');
});
Route::get('/add', function(){
    return view('add');
});



Route::get('/tents/create', [TentController::class, 'create']);
Route::post('/tents', [TentController::class, 'store']);
Route::get('/tents', [TentController::class, 'index']);
Route::get('/tents/{tent}/edit', [TentController::class, 'edit']);
Route::post('/tents/{tent}/update', [TentController::class, 'update']);
Route::post('/tents/{tent}/delete', [TentController::class, 'destroy']);


Route::get('/user',[BookingController::class,'user'])->name('booking.index');
Route::post('/user',[BookingController::class,'checkAvailability'])->name('booking.checkAvailability');
Route::post('/checkout',[BookingController::class,'checkout'])->name('booking.checkout');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');

