<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FornissureInvoice;
use App\Models\Order;
use App\Models\User;
use App\Notifications\InvoiceCreatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GeneratePaymentInvoices extends Command
{
    protected $signature = 'invoices:generate-payment';
    protected $description = 'Generate payment invoices for fornissures every 3 days';

    public function handle(): int
    {
        $start = Carbon::now()->subDays(3)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        // get all fornissures who have confirmed orders in this period
        $fornissures = User::whereHas('roles', function ($query) {
            $query->where('name', 'fornissure');
        })->get();

        foreach ($fornissures as $fornissure) {
            $orders = Order::where('fornissure_id', $fornissure->id)
                ->whereNotNull('confirmation_price_id')
                ->whereBetween('created_at', [$start, $end])
                ->whereDoesntHave('invoices')
                ->with('confirmationPrice')
                ->get();

            if ($orders->isEmpty()) {
                continue;
            }

            $amount = $orders->sum(fn($order) => optional($order->confirmationPrice)->amount ?? 0);

            $invoice = FornissureInvoice::create([
                'fornissure_id' => $fornissure->id,
                'reference'     => 'INV-PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
                'type'          => 'payment',
                'period_start'  => $start->toDateString(),
                'period_end'    => $end->toDateString(),
                'amount'        => $amount,
                'status'        => 'not_paid',
            ]);

            $invoice->orders()->attach($orders->pluck('id'));
            //notify fornissure
            Notification::send($fornissure, new InvoiceCreatedNotification($invoice));
            $this->info("✅ Payment invoice {$invoice->reference} created for fornissure {$fornissure->id} ({$orders->count()} orders, total €{$amount}).");
        }

        return 0;
    }
}
