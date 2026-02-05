<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentController;

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
