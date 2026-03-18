<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Booking;
use Carbon\Carbon;

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        //
        $payload = $event->payload;
        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            $bookingId = $session['metadata']['booking_id'] ?? null;

            $booking = Booking::find($bookingId);
            if ($booking && $booking->status === "pending"){
                if ($booking->expires_at > now() && $session['payment_status'] === 'paid'){
                    $booking->update([
                        'status' => 'confirmed'
                    ]);
                }else{
                    $booking->update([
                        'status' => 'cancelled'
                    ]);
                }
            }
            
        }

    }
}
