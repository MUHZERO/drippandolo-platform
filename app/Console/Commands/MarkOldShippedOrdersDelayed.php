<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderDelayedNotification;
use Carbon\Carbon;

class MarkOldShippedOrdersDelayed extends Command
{
    protected $signature = 'orders:mark-delayed';
    protected $description = 'Mark shipped orders as delayed if not updated for 15 days, and notify fornissure, operator, admin';

    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(15);

        $orders = Order::where('status', 'shipped')
            ->where('updated_at', '<', $cutoff)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No old shipped orders found.');
            return 0;
        }

        foreach ($orders as $order) {
            $order->update(['status' => 'delayed']);

            // notify roles
            $users = User::whereHas('roles', fn($q) => $q->whereIn('name', ['fornissure', 'operator', 'admin']))->get();
            Notification::send($users, new OrderDelayedNotification($order));

            $this->info("Order #{$order->id} marked as delayed and notifications sent.");
        }

        return 0;
    }
}
