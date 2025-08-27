<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoOrdersYesterdayNotification extends Notification
{
    use Queueable;

    protected string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail']; // or just ['database'] if you don’t want emails
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key'    => 'notifications.no_orders_yesterday',
            'params' => ['date' => $this->date],
            'date'   => $this->date,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.no_orders_yesterday.subject', [], 'it')) // force IT
            ->line(__('notifications.no_orders_yesterday.body', ['date' => $this->date], 'it'))
            ->action(__('notifications.no_orders_yesterday.action', [], 'it'), url('/orders'));
    }
}
