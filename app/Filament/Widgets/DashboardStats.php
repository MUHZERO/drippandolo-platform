<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Support\DashboardService;

class DashboardStats extends BaseWidget
{

    protected $listeners = ['filtersUpdated' => 'applyFilters'];

    public ?int $month = null;
    public ?int $year = null;

    public function applyFilters($month, $year): void
    {
        $this->month = $month;
        $this->year = $year;
    }

    protected function getCards(): array
    {
        $month = $this->month ?? now()->month;
        $year  = $this->year ?? now()->year;

        $stats = DashboardService::getStats($month, $year);

        return [
            Stat::make(__('dashboard.revenue'), '€' . number_format($stats['revenue'], 2))
                ->icon('heroicon-o-currency-euro')
                ->color('success'), // green

            Stat::make(__('dashboard.spend'), '€' . number_format($stats['spend'], 2))
                ->icon('heroicon-o-credit-card')
                ->color('danger'), // red

            Stat::make(__('dashboard.net'), '€' . number_format($stats['net'], 2))
                ->icon('heroicon-o-calculator')
                ->color($stats['net'] >= 0 ? 'success' : 'danger'), // green if positive, red if negative

            Stat::make(__('dashboard.orders'), $stats['orders']['total'])
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'), // blue
        ];
    }
}
