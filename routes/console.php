<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\DailyOrdersSummary;
use App\Console\Commands\NotifyUnconfirmedOrders;
use App\Console\Commands\NotifyMissingRevenue;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command(DailyOrdersSummary::class)
    ->dailyAt('01:00');

Schedule::command(NotifyUnconfirmedOrders::class)
    ->hourly();

Schedule::command(NotifyMissingRevenue::class)
    ->weekdays()
    ->dailyAt('09:00');

Schedule::command('orders:remind-operators')
    ->dailyAt('02:00');

Schedule::command('orders:mark-delayed')
    ->dailyAt('03:00');

Schedule::command('invoices:generate-return')
    ->cron('0 2 */20 * *'); // every 20 days at 02:00

Schedule::command('invoices:generate-payment')
    ->cron('0 3 */3 * *'); // every 3 days at 03:00
