<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailyOrdersSummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $stats;
    public string $date;

    public function __construct(array $stats, string $date)
    {
        $this->stats = $stats;
        $this->date  = $date;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key'   => 'notifications.daily_summary',
            'date'  => $this->date,
            'stats' => $this->stats,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        app()->setLocale('it'); // send mails in IT

        return (new MailMessage)
            ->subject(__('notifications.daily_summary.subject', ['date' => $this->date]))
            ->line(__('notifications.daily_summary.intro', ['date' => $this->date]))
            ->line(__('notifications.daily_summary.total', ['count' => $this->stats['total']]))
            ->line(__('notifications.daily_summary.shipped', ['count' => $this->stats['shipped']]))
            ->line(__('notifications.daily_summary.delivered', ['count' => $this->stats['delivered']]))
            ->line(__('notifications.daily_summary.canceled', ['count' => $this->stats['canceled']]));
    }
}
