<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public string $token;
    public string $confirmUrl;

    public function __construct(Appointment $appointment, string $token, string $frontendUrl)
    {
        $this->appointment = $appointment;
        $this->token = $token;
        $this->confirmUrl = rtrim($frontendUrl, '/') . '/appointments/confirm?token=' . $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm Your QAlert Appointment',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
