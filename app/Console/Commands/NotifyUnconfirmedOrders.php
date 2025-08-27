<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Notifications\OrderNeedsConfirmationNotification;
use Carbon\Carbon;

class NotifyUnconfirmedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example: php artisan orders:notify-unconfirmed
     */
    protected $signature = 'orders:notify-unconfirmed';

    /**
     * The console command description.
     */
    protected $description = 'Notify fornissures about orders without confirmation_price_id older than 8 hours';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(8);

        $orders = Order::whereNull('confirmation_price_id')
            ->where('created_at', '<', $cutoff)
            ->get();

        foreach ($orders as $order) {
            if ($order->fornissure) {
                $order->fornissure->notify(new OrderNeedsConfirmationNotification($order));
                $this->info("Notified fornissure #{$order->fornissure_id} about order #{$order->id}");
            }
        }

        return Command::SUCCESS;
    }
}

