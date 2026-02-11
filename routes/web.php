<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
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


Route::get('/user',[BookingController::class,'user']);

