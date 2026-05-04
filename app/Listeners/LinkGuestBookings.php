<?php

namespace App\Listeners;

use App\Models\Booking;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;

class LinkGuestBookings
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        if ($user) {
            // Find any guest bookings with the same email and link them to the user
            Booking::where('customer_email', $user->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }
    }
}
