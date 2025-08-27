<?php

namespace App\Filament\Resources\SpendResource\Pages;

use App\Filament\Resources\SpendResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpend extends EditRecord
{
    protected static string $resource = SpendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
