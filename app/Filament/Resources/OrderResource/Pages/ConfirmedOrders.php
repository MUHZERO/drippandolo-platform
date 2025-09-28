<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ConfirmedOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    public function getTitle(): string
    {
        return __('resources.pages.orders_confirmed.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.pages.orders_confirmed.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return OrderResource::getNavigationGroup();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewPendingOrders')
                ->label(__('resources.pages.orders.plural'))
                ->icon('heroicon-o-clock')
                ->url(static::getResource()::getUrl()),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where(function (Builder $query) {
                $query->whereNotNull('tracking_number')
                    ->where('tracking_number', '!=', '');
            });
    }
}
