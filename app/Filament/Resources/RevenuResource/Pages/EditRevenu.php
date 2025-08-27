<?php

namespace App\Filament\Resources\RevenuResource\Pages;

use App\Filament\Resources\RevenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRevenu extends EditRecord
{
    protected static string $resource = RevenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
