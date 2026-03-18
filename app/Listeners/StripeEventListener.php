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
            if ($booking && $booking->status === "pending") {
                if ($session['payment_status'] === 'paid') {
                    $booking->update([
                        'status' => 'confirmed',
                        'customer_name' => $session['customer_details']['name'],
                        'customer_phone' => $session['customer_details']['phone'],
                        'stripe_payment_intent_id' => $session['payment_intent'] ?? null,

                    ]);
                } else {
                    $booking->update([
                        'status' => 'cancelled'
                    ]);
                }
            }
        }
    }
}
