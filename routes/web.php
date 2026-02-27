<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return redirect('/user');
});
Route::get('/booking', function () {
    return view('booking');
});
Route::get('/all', [BookingController::class, 'myBookings'])->name('user.bookings')->middleware('auth');
Route::get('/add', function () {
    return view('add');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $stats = [
            'total_tents' => \App\Models\Tent::count(),
            'total_slots' => \App\Models\Slot::count(),
            'active_bookings' => \App\Models\Booking::where('status', 'confirmed')->count(),
            'monthly_revenue' => \App\Models\Booking::where('status', 'confirmed')->where('created_at', '>=', now()->subDays(30))->sum('total_price'),
            'recent_tents' => \App\Models\Tent::latest()->take(3)->get(),
            'recent_bookings' => \App\Models\Booking::with('user', 'slot.tent')->latest()->take(6)->get(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('admin.dashboard');

    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'adminUpdateStatus'])->name('admin.bookings.status');

    Route::get('/admin/tents/create', [TentController::class, 'create']);
    Route::post('/admin/tents', [TentController::class, 'store']);
    Route::get('/admin/tents', [TentController::class, 'index']);
    Route::get('/admin/tents/{tent}/edit', [TentController::class, 'edit']);
    Route::post('/admin/tents/{tent}/update', [TentController::class, 'update']);
    Route::post('/admin/tents/{tent}/delete', [TentController::class, 'destroy']);
});


Route::get('/user', [BookingController::class, 'user'])->name('booking.index');
Route::post('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::get('/viewer-count/{id}', [BookingController::class, 'getViewerCount'])->name('booking.viewerCount');

Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

