<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class MissingRevenueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Carbon $date) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.missing_revenue.subject'))
            ->line(__('notifications.missing_revenue.line', [
                'date' => $this->date->translatedFormat('d F Y'),
            ]))
            ->action(__('notifications.missing_revenue.action'), url('/revenues'))
            ->line(__('notifications.missing_revenue.footer'));
    }

    public function toArray(object $notifiable): array
    {
        // Store translation key instead of raw text
        return [
            'key' => 'notifications.missing_revenue.db',
            'date' => $this->date->toDateString(),
        ];
    }
}
