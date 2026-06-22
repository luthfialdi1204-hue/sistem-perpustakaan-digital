<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly int $expiryMinutes = 5
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Kode OTP Perpustakaan Digital Polibatam',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'code'          => $this->code,
                'expiryMinutes' => $this->expiryMinutes,
            ],
        );
    }
}