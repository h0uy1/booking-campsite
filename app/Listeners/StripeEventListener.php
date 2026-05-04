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
                $updateData = [
                    'status' => 'confirmed',
                    'stripe_payment_intent_id' => $intent['id'],
                ];

                // Link to user if they already have an account
                if (!$booking->user_id) {
                    $user = \App\Models\User::where('email', $booking->customer_email)->first();
                    if ($user) {
                        $updateData['user_id'] = $user->id;
                    }
                }

                $booking->update($updateData);

                // Generate PDF receipt
                $pdf = Pdf::loadView('pdf.receipt', ['booking' => $booking]);

                // Determine recipient email
                $recipientEmail = $booking->customer_email ?? $booking->user?->email;

                if ($recipientEmail) {
                    try {
                        Resend::emails()->send([
                            'from' => 'Tam Durian Farm Campsite <bookings@mail.tamdurianfarm.site>',
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
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to send booking confirmation email for Booking #{$booking->id}: " . $e->getMessage());
                    }
                }
            }
        }

        if ($payload['type'] === 'payment_intent.payment_failed') {
            $intent = $payload['data']['object'];

            $bookingId = $intent['metadata']['booking_id'] ?? null;
            $booking = Booking::find($bookingId);

            if ($booking) {
                $booking->update([
                    'status' => 'cancelled',
                ]);
            }
        }
    }
}
