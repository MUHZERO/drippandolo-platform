<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderNeedsConfirmationNotification extends Notification
{
    use Queueable;

    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * To Database (we store translation key + payload).
     */
    public function toDatabase($notifiable): array
    {
        return [
            'key'      => 'notifications.needs_confirmation',
            'order_id' => $this->order->id,
        ];
    }

    /**
     * To Mail (default Italian).
     */
    public function toMail($notifiable): MailMessage
    {
        app()->setLocale('it'); // force Italian

        return (new MailMessage)
            ->subject(__('notifications.needs_confirmation.subject', ['order' => $this->order->id]))
            ->line(__('notifications.needs_confirmation.line', ['order' => $this->order->id]))
            ->action(__('notifications.needs_confirmation.action'), url("/orders/{$this->order->id}"));
    }
}

