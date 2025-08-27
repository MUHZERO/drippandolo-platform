<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Support\DashboardService;

class DashboardChart extends ChartWidget
{
    protected $listeners = ['filtersUpdated' => 'applyFilters'];

    protected int | string | array $columnSpan = 'full'; // full width
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return __('dashboard.monthly_overview');
    }

    public ?int $month;
    public ?int $year;

    public function applyFilters($month, $year): void
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function getColumns(): int | array
    {
        return 1; // single column layout
    }

    protected function getData(): array
    {
        $month = $this->month ?? now()->month;
        $year  = $this->year ?? now()->year;

        $daily = DashboardService::getDailyData($month, $year);

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.revenue'),
                    'data' => $daily['revenue'],
                    'borderColor' => '#16a34a',
                ],
                [
                    'label' => __('dashboard.spend'),
                    'data' => $daily['spend'],
                    'borderColor' => '#dc2626',
                ],
                [
                    'label' => __('dashboard.net'),
                    'data' => $daily['net'],
                    'borderColor' => '#2563eb',
                ],
                [
                    'label' => __('dashboard.orders'),
                    'data' => $daily['orders'],
                    'borderColor' => '#2196f3',
                ],
            ],
            'labels' => $daily['days'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
