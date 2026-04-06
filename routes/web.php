<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BlockoutDateController;
use App\Http\Controllers\SlotStatusController;

Route::get('/', function () {
    return redirect('/user');
});
Route::get('/home', function () {
    return redirect('/user');
});
Route::get('/booking', function () {
    return view('booking');
});
Route::get('/all', [BookingController::class, 'myBookings'])->name('user.bookings')->middleware('auth');
Route::get('/my-bookings/{id}', [BookingController::class, 'showBooking'])->name('user.booking.show')->middleware('auth');
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
    Route::get('/admin/bookings/create', [BookingController::class, 'adminCreate'])->name('admin.bookings.create');
    Route::get('/admin/bookings/tents', [BookingController::class, 'getTents'])->name('admin.bookings.tents');
    Route::post('/admin/bookings', [BookingController::class, 'adminStore'])->name('admin.bookings.store');
    Route::get('/admin/bookings/{booking}/edit', [BookingController::class, 'adminEdit'])->name('admin.bookings.edit');
    Route::put('/admin/bookings/{booking}', [BookingController::class, 'adminUpdate'])->name('admin.bookings.update');
    Route::get('/admin/bookings/occupancy', [BookingController::class, 'adminOccupancy'])->name('admin.bookings.occupancy');
    Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'adminUpdateStatus'])->name('admin.bookings.status');
    
    // Blockout Dates
    Route::get('/admin/blockouts', [BlockoutDateController::class, 'index'])->name('admin.blockouts.index');
    Route::get('/admin/blockouts/create', [BlockoutDateController::class, 'create'])->name('admin.blockouts.create');
    Route::post('/admin/blockouts', [BlockoutDateController::class, 'store'])->name('admin.blockouts.store');
    Route::delete('/admin/blockouts/{blockout}', [BlockoutDateController::class, 'destroy'])->name('admin.blockouts.destroy');

    // Slot Status Dashboard
    Route::get('/admin/slots', [SlotStatusController::class, 'index'])->name('admin.slots.index');
    Route::post('/admin/slots/update-state', [SlotStatusController::class, 'updateState'])->name('admin.slots.update_state');

    // Admin Settings
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
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

Route::get('checkout/success', [BookingController::class, 'checkoutSuccess'])->name('checkout.success');

Route::get('checkout/cancel', [BookingController::class, 'checkoutCancel'])->name('checkout.cancel');
