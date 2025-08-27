<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public $order) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.order_created.subject', [], 'it')) // Force Italian
            ->line(__('notifications.order_created.body', ['id' => $this->order->id], 'it'))
            ->action(__('notifications.order_created.action', [], 'it'), url("/orders/{$this->order->id}"));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key' => 'notifications.order_created',
            'params' => ['id' => $this->order->id],
            'order_id' => $this->order->id,
        ];
    }
}

