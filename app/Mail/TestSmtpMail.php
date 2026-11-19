<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientEmail;
    public string $sentAt;

    /**
     * Create a new message instance.
     */
    public function __construct(string $recipientEmail = '')
    {
        $this->recipientEmail = $recipientEmail;
        $this->sentAt = now()->translatedFormat('d F Y H:i:s') . ' WIB';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tes Integrasi Gmail SMTP - SKMS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test_smtp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
