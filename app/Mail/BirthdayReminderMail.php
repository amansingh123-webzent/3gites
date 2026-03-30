<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BirthdayReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User  $celebrant,
        public bool  $isCelebrant  = false,
        public ?User $recipient    = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->isCelebrant
            ? "🎂 Happy Birthday from the Class of 1975, {$this->celebrant->name}!"
            : "🎂 Today is {$this->celebrant->name}'s Birthday!";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.birthday-reminder');
    }
}
