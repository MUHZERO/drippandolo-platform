<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FornissureInvoice;
use App\Models\Order;
use App\Models\User;
use App\Notifications\InvoiceCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GeneratePaymentInvoices extends Command
{
    protected $signature = 'invoices:generate-payment';
    protected $description = 'Generate weekly payment invoices for fornissures (every Wednesday)';

    public function handle(): int
    {

        // get all fornissures who have confirmed orders in this period
        $fornissures = User::whereHas('roles', function ($query) {
            $query->where('name', 'fornissure');
        })->get();

        foreach ($fornissures as $fornissure) {
            $orders = Order::where('fornissure_id', $fornissure->id)
                ->whereNotNull('confirmation_price_id')
                ->whereDoesntHave('invoices')
                ->whereNotNull('tracking_number')
                ->where('tracking_number', '!=', '')
                ->with('confirmationPrice')
                ->orderBy('created_at')
                ->get();

            $orders->chunk(50)->each(function ($batch) use ($fornissure) {
                if ($batch->count() < 50) {
                    return;
                }

                $amount = $batch->sum(fn($order) => optional($order->confirmationPrice)->price ?? 0);

                $invoice = FornissureInvoice::create([
                    'fornissure_id' => $fornissure->id,
                    'reference'     => 'INV-PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
                    'type'          => 'payment',
                    'period_start'  => $batch->first()->created_at->toDateString(),
                    'period_end'    => $batch->last()->created_at->toDateString(),
                    'amount'        => $amount,
                    'status'        => 'not_paid',
                ]);

                $invoice->orders()->attach($batch->pluck('id'));
                Notification::send($fornissure, new InvoiceCreatedNotification($invoice));
                $this->info("✅ Payment invoice {$invoice->reference} created for fornissure {$fornissure->id} ({$batch->count()} orders, total €{$amount}).");
            });
        }

        return 0;
    }
}
