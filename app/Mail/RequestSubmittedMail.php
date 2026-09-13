<?php

namespace App\Mail;

use App\Models\StudentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudentRequest $studentRequest;
    public string $recipientName;
    public bool $isForStudent;

    public function __construct(StudentRequest $studentRequest, string $recipientName, bool $isForStudent = true)
    {
        $this->studentRequest = $studentRequest;
        $this->recipientName = $recipientName;
        $this->isForStudent = $isForStudent;
    }

    public function envelope(): Envelope
    {
        $subject = $this->isForStudent
            ? "Confirmation dépôt requête #{$this->studentRequest->reference_code} — Devia Technologic"
            : "Nouvelle requête académique #{$this->studentRequest->reference_code} à traiter";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-submitted',
        );
    }
}
