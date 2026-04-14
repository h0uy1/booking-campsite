<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $pdfContent)
    {
        $this->booking = $booking;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Booking Receipt - #BK-' . sprintf('%05d', $this->booking->id),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Booking-Receipt-BK-' . sprintf('%05d', $this->booking->id) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
