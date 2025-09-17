<?php

namespace App\Filament\Resources\FornissureInvoiceResource\Pages;

use App\Filament\Resources\FornissureInvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListFornissureInvoices extends ListRecords
{
    protected static string $resource = FornissureInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
