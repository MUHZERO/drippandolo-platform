<?php

namespace App\Filament\Resources\ConfirmationPriceResource\Pages;

use App\Filament\Resources\ConfirmationPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConfirmationPrice extends EditRecord
{
    protected static string $resource = ConfirmationPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
