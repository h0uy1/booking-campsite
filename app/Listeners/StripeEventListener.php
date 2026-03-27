<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;
use App\Models\Booking;
use Carbon\Carbon;
use Resend\Laravel\Facades\Resend;
use Barryvdh\DomPDF\Facade\Pdf;

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
                        'customer_phone' => $session['customer_details']['phone'],
                        'stripe_payment_intent_id' => $session['payment_intent'] ?? null,

                    ]);

                    // Generate PDF receipt
                    $pdf = Pdf::loadView('pdf.receipt', ['booking' => $booking]);

                    Resend::emails()->send([
                        'from' => 'Tam Durian Farm Campsite <onboarding@resend.dev>',
                        'to' => [$booking->user?->email ?? $booking->customer_email ?? $session['customer_details']['email']],
                        'subject' => 'Booking Confirmed - Tam Durian Farm Campsite',
                        'html' => view('emails.booking_confirmation', ['booking' => $booking])->render(),
                        'attachments' => [
                            [
                                'filename' => 'receipt-BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf',
                                'content' => base64_encode($pdf->output()),
                            ]
                        ]
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
