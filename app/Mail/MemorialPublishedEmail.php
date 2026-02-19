<?php

namespace App\Mail;

use App\Models\Memorial;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemorialPublishedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Memorial $memorial)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Мемориал опубликован'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.memorial-published'
        );
    }
}

