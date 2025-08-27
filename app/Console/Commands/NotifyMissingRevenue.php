<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Revenu;
use App\Notifications\MissingRevenueNotification;
use Carbon\Carbon;

class NotifyMissingRevenue extends Command
{
    protected $signature = 'revenue:notify-missing';

    protected $description = 'Notify admins and managers if daily revenue has not been entered (Mon–Fri)';

    public function handle(): int
    {
        $today = Carbon::now('Europe/Rome');

        // Skip weekends
        if ($today->isSaturday() || $today->isSunday()) {
            $this->info("Weekend — no check needed.");
            return self::SUCCESS;
        }

        // Check if today's revenue exists
        $hasRevenue = Revenu::whereDate('date', $today->toDateString())->exists();

        if (! $hasRevenue) {
            $this->info("No revenue entered for {$today->toDateString()} — sending notifications...");

            $admins = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->get();

            $recipients = $admins->unique('id');

            foreach ($recipients as $user) {
                $user->notify(new MissingRevenueNotification($today));
            }
        } else {
            $this->info("Revenue already entered for today.");
        }

        return self::SUCCESS;
    }
}

