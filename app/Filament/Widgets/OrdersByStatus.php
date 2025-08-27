<?php

namespace App\Filament\Widgets;

use App\Support\DashboardService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersByStatus extends BaseWidget
{
    protected $listeners = ['filtersUpdated' => 'applyFilters'];
    public ?int $month;
    public ?int $year;
    public function applyFilters($month, $year): void
    {
        $this->month = $month;
        $this->year = $year;
    }

    protected function getStats(): array
    {
        $month = $this->month ?? now()->month;
        $year  = $this->year ?? now()->year;

        $stats = DashboardService::getStats($month, $year);

        return [
            Stat::make(__('dashboard.orders_delayed'), $stats['orders']['delayed'])
                ->icon('heroicon-o-clock')
                ->color('warning'), // yellow/orange

            Stat::make(__('dashboard.orders_shipped'), $stats['orders']['shipped'])
                ->icon('heroicon-o-truck')
                ->color('info'), // teal/blue

            Stat::make(__('dashboard.orders_delivered'), $stats['orders']['delivered'])
                ->icon('heroicon-o-check-badge')
                ->color('success'), // green

            Stat::make(__('dashboard.orders_canceled'), $stats['orders']['canceled'])
                ->icon('heroicon-o-x-circle')
                ->color('danger'), // red
        ];
    }
}
