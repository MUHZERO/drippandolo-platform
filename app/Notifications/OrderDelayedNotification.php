<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDelayedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key' => 'notifications.order_delayed',
            'params' => ['id' => $this->order->id],
            'order_id' => $this->order->id,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.order_delayed.subject', ['id' => $this->order->id], 'it'))
            ->line(__('notifications.order_delayed.body', ['id' => $this->order->id], 'it'))
            ->action(__('notifications.order_delayed.action', [], 'it'), url("/orders/{$this->order->id}"));
    }
}

