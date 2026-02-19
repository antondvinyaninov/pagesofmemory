<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\Memory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommentNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Memory $memory, public Comment $comment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новый комментарий к вашему воспоминанию'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-comment-notification'
        );
    }
}

