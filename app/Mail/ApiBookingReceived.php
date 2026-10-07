<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApiBookingReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New partner API booking '.$this->booking->reference.' from '.$this->booking->apiClient?->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.api.booking-received',
        );
    }
}
