<?php

namespace App\Filament\Resources\FornissureInvoiceResource\Pages;

use App\Filament\Resources\FornissureInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFornissureInvoice extends EditRecord
{
    protected static string $resource = FornissureInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) {

            return [
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }
}
