<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\OrdersByStatus;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = null;

    public function getTitle(): Htmlable | string
    {
        return __('resources.pages.dashboards.singular');
    }

    // only register in menu if admin
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public static function canAccess(): bool
    {
        // Only admins can see the dashboard
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public ?int $month = null;
    public ?int $year = null;

    public function mount(): void
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('month')
                ->label(__('dashboard.month'))
                ->options(
                    collect(range(1, 12))->mapWithKeys(fn($m) => [
                        $m => \Carbon\Carbon::create()->month($m)->translatedFormat('F')
                    ])
                )
                ->default(now()->month),

            Forms\Components\Select::make('year')
                ->label(__('dashboard.year'))
                ->options(
                    collect(range(2023, now()->year))->mapWithKeys(fn($y) => [$y => $y])
                )
                ->default(now()->year),
        ]);
    }

    public  function getWidgets(): array
    {
        return [
            DashboardStats::class,
            DashboardChart::class,
            OrdersByStatus::class,
        ];
    }
    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardFilters::class,
        ];
    }
}
