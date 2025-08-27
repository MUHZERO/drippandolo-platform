<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FornissureInvoice;

class InvoiceCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public FornissureInvoice $invoice) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.invoice_created.subject', [
                'id'   => $this->invoice->id,
                'type' => $this->invoice->type,
            ], 'it')) // Force Italian
            ->line(__('notifications.invoice_created.body', [
                'id'   => $this->invoice->id,
                'type' => $this->invoice->type,
            ], 'it'))
            ->action(__('notifications.invoice_created.action', [], 'it'), url("/fornissure-invoices/{$this->invoice->id}"));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'key' => 'notifications.invoice_created',
            'params' => [
                'id'   => $this->invoice->id,
                'type' => $this->invoice->type,
            ],
            'invoice_id' => $this->invoice->id,
        ];
    }
}

