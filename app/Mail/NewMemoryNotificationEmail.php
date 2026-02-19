<?php

namespace App\Mail;

use App\Models\Memorial;
use App\Models\Memory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMemoryNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Memorial $memorial, public Memory $memory)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новое воспоминание на странице мемориала'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-memory-notification'
        );
    }
}

