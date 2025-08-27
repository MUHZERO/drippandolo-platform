<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\Widget;

class DashboardFilters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.dashboard-filters';
    protected int | string | array $columnSpan = 'full';

    public ?int $month = null;
    public ?int $year = null;

    public function mount(): void
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    // Responsive columns: 1 on mobile, 2 on medium+, 4 on xl+
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }

    public function updated($property)
    {
        $this->dispatch('filtersUpdated', month: $this->month, year: $this->year);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2) // 2 columns
                ->schema(
                    [
                        Forms\Components\Select::make('month')
                            ->label(__('dashboard.month'))
                            ->options(
                                collect(range(1, 12))->mapWithKeys(fn($m) => [
                                    $m => \Carbon\Carbon::create()->month($m)->translatedFormat('F')
                                ])
                            )
                            ->reactive()
                            ->default(now()->month),

                        Forms\Components\Select::make('year')
                            ->label(__('dashboard.year'))
                            ->options(
                                collect(range(2023, now()->year))->mapWithKeys(fn($y) => [$y => $y])
                            )
                            ->reactive()
                            ->default(now()->year),
                    ]
                ),
        ];
    }
}
