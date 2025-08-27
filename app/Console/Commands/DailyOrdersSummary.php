<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Notifications\DailyOrdersSummaryNotification;
use Illuminate\Support\Carbon;

class DailyOrdersSummary extends Command
{
    protected $signature = 'orders:daily-summary';
    protected $description = 'Send daily orders summary to admins';

    public function handle(): int
    {
        $yesterday = Carbon::yesterday()->toDateString();

        $query = Order::whereDate('created_at', $yesterday);

        $stats = [
            'total'     => (clone $query)->count(),
            'shipped'   => (clone $query)->where('status', 'shipped')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'canceled'  => (clone $query)->where('status', 'canceled')->count(),
        ];

        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new DailyOrdersSummaryNotification($stats, $yesterday));
        }

        $this->info("Daily orders summary sent for {$yesterday}.");

        return self::SUCCESS;
    }
}
