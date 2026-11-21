<?php

namespace App\Mail;

use App\Models\Faq;
use App\Models\QuestionSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FaqAnsweredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param QuestionSubmission $submission
     * @param Faq $faq
     * @param string $scenario 'new_faq' | 'existing_faq'
     */
    public function __construct(
        public QuestionSubmission $submission,
        public Faq $faq,
        public string $scenario = 'new_faq'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjectPrefix = $this->scenario === 'new_faq'
            ? '[SKMS UBAYA] Pertanyaan Anda Telah Diterbitkan Menjadi FAQ Resmi'
            : '[SKMS UBAYA] Jawaban Resmi untuk Pertanyaan Anda';

        return new Envelope(
            subject: "{$subjectPrefix} (Tiket #{$this->submission->id})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.faq_answered',
            with: [
                'submission'   => $this->submission,
                'faq'          => $this->faq,
                'scenario'     => $this->scenario,
                'studentEmail' => $this->submission->student_email,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
