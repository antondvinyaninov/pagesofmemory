<?php

namespace App\Services;

use App\Mail\MemorialCreatedEmail;
use App\Mail\MemorialPublishedEmail;
use App\Mail\NewCommentNotificationEmail;
use App\Mail\NewMemoryNotificationEmail;
use App\Mail\NewsletterCampaignEmail;
use App\Mail\WelcomeEmail;
use App\Models\Comment;
use App\Models\Memorial;
use App\Models\Memory;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public function sendWelcomeEmail(User $user): void
    {
        $this->sendSafely($user->email, new WelcomeEmail($user), 'welcome_email');
    }

    public function sendMemorialCreatedEmail(User $user, Memorial $memorial): void
    {
        $this->sendSafely($user->email, new MemorialCreatedEmail($user, $memorial), 'memorial_created_email');
    }

    public function sendMemorialPublishedEmail(User $user, Memorial $memorial): void
    {
        $this->sendSafely($user->email, new MemorialPublishedEmail($user, $memorial), 'memorial_published_email');
    }

    public function sendNewMemoryNotification(Memorial $memorial, Memory $memory): void
    {
        if (!$memorial->relationLoaded('user')) {
            $memorial->load('user');
        }
        if (!$memory->relationLoaded('user')) {
            $memory->load('user');
        }

        if (!$memorial->user || !$memory->user) {
            return;
        }

        // Не отправляем уведомление, если владелец мемориала добавил воспоминание сам.
        if ($memorial->user_id === $memory->user_id) {
            return;
        }

        $this->sendSafely(
            $memorial->user->email,
            new NewMemoryNotificationEmail($memorial, $memory),
            'new_memory_notification'
        );
    }

    public function sendNewCommentNotification(Memory $memory, Comment $comment): void
    {
        if (!$memory->relationLoaded('user')) {
            $memory->load('user', 'memorial');
        }
        if (!$comment->relationLoaded('user')) {
            $comment->load('user');
        }

        if (!$memory->user || !$comment->user) {
            return;
        }

        // Не отправляем уведомление, если автор воспоминания комментирует сам себя.
        if ($memory->user_id === $comment->user_id) {
            return;
        }

        $this->sendSafely(
            $memory->user->email,
            new NewCommentNotificationEmail($memory, $comment),
            'new_comment_notification'
        );
    }

    public function sendTestEmail(string $email, string $subject, string $content): bool
    {
        return $this->sendSafely(
            $email,
            new NewsletterCampaignEmail($subject, $content, true),
            'newsletter_test'
        );
    }

    public function sendCampaign(string $audience, string $subject, string $content): int
    {
        $recipients = $this->getAudienceRecipients($audience);
        $sent = 0;

        foreach ($recipients as $email) {
            if ($this->sendSafely($email, new NewsletterCampaignEmail($subject, $content, false), 'newsletter_campaign')) {
                $sent++;
            }
        }

        return $sent;
    }

    private function getAudienceRecipients(string $audience): array
    {
        $query = User::query()
            ->whereNotNull('email')
            ->where('email', '!=');

        if ($audience === 'published_memorial_owners') {
            $query->whereHas('memorials', function ($memorials) {
                $memorials->where('status', 'published');
            });
        }

        return $query
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function sendSafely(string $email, Mailable $mailable, string $context): bool
    {
        if (trim($email) === '') {
            return false;
        }

        try {
            Mail::to($email)->send($mailable);
            return true;
        } catch (\Throwable $e) {
            Log::error('Ошибка отправки email', [
                'context' => $context,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
