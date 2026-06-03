<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public ?string $senderPhone,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('contact.mail.subject', ['name' => $this->senderName]),
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact.message-received',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
