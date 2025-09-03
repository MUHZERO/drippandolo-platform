<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NoOrdersYesterdayNotification;
use Carbon\Carbon;

class RemindOperatorsIfNoOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'orders:remind-operators';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Send reminder to operators at 02:00 if no orders were created yesterday';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $ordersCount = Order::whereDate('created_at', $yesterday)->count();
        if ($ordersCount == 0 && Order::count() > 0) {
            $operators = User::whereHas('roles', fn($q) => $q->where('name', 'operator'))->get();
            if ($operators->isNotEmpty()) {
                Notification::send($operators, new NoOrdersYesterdayNotification($yesterday));
                $this->info("Reminder sent to operators: No orders found for {$yesterday}.");
            } else {
                $this->warn('No operators found to notify.');
            }
        } else {
            $this->info("Orders found for {$yesterday}, no reminder sent.");
        }
        return 0;
    }
}
