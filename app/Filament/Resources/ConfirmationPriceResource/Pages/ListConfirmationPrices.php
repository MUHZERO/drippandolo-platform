<?php

namespace App\Filament\Resources\ConfirmationPriceResource\Pages;

use App\Filament\Resources\ConfirmationPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConfirmationPrices extends ListRecords
{
    protected static string $resource = ConfirmationPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
