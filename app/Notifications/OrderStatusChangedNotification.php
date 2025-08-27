<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public $order, public $oldStatus, public $newStatus) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.order_status_changed.subject', [], 'it'))
            ->line(__('notifications.order_status_changed.body', [
                'id' => $this->order->id,
                'old' => $this->oldStatus,
                'new' => $this->newStatus,
            ], 'it'))
            ->action(__('notifications.order_status_changed.action', [], 'it'), url("/orders/{$this->order->id}"));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key' => 'notifications.order_status_changed',
            'params' => [
                'id' => $this->order->id,
                'old' => $this->oldStatus,
                'new' => $this->newStatus,
            ],
            'order_id' => $this->order->id,
        ];
    }
}

