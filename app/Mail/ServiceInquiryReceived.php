<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceInquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $serviceName,
        public string $senderName,
        public string $senderEmail,
        public string $senderPhone,
        public ?string $travelDate,
        public ?int $people,
        public ?string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('services.show.form.mail_subject', ['service' => $this->serviceName, 'name' => $this->senderName]),
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.services.inquiry-received',
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
