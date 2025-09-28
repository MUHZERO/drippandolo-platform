<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('viewConfirmedOrders')
                ->label(__('resources.pages.orders_confirmed.plural'))
                ->icon('heroicon-o-check-circle')
                ->url(static::getResource()::getUrl('confirmed')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where(function (Builder $query) {
                $query->whereNull('tracking_number')
                    ->orWhere('tracking_number', '');
            });
    }
}
