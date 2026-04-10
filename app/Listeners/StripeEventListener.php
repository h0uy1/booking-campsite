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
        if ($payload['type'] === 'payment_intent.succeeded') {
            $intent = $payload['data']['object'];

            $bookingId = $intent['metadata']['booking_id'] ?? null;
            $booking = Booking::with('user', 'slot.tent')->find($bookingId);

            if ($booking && $booking->status === "pending") {
                $booking->update([
                    'status' => 'confirmed',
                    'stripe_payment_intent_id' => $intent['id'],
                ]);

                // Generate PDF receipt
                $pdf = Pdf::loadView('pdf.receipt', ['booking' => $booking]);

                // Determine recipient email
                $recipientEmail = $booking->customer_email ?? $booking->user?->email;

                if ($recipientEmail) {
                    Resend::emails()->send([
                        'from' => 'Tam Durian Farm Campsite <onboarding@resend.dev>',
                        'to' => [$recipientEmail],
                        'subject' => 'Booking Confirmed - Tam Durian Farm Campsite',
                        'html' => view('emails.booking_confirmation', ['booking' => $booking])->render(),
                        'attachments' => [
                            [
                                'filename' => 'receipt-BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf',
                                'content' => base64_encode($pdf->output()),
                            ]
                        ]
                    ]);
                }
            }
        }
    }
}
