<?php

namespace App\Mail;

use App\Models\StudentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudentRequest $studentRequest;
    public ?string $comment;

    public function __construct(StudentRequest $studentRequest, ?string $comment = null)
    {
        $this->studentRequest = $studentRequest;
        $this->comment = $comment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Mise à jour dossier #{$this->studentRequest->reference_code} — Devia Technologic",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-status-updated',
        );
    }
}
