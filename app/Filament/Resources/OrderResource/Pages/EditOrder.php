<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user   = auth()->user();
        $record = $this->record;

        if ($user->hasRole('operator')) {
            unset($data['confirmation_price_id']);
        }

        if ($user->hasRole('fornissure')) {
            // allow only status + confirmation_price_id
            $allowed = ['status', 'confirmation_price_id'];

            // if order already invoiced, remove confirmation_price_id
            if ($record->invoices()->exists()) {
                $allowed = ['status'];
            }

            $data = array_intersect_key($data, array_flip($allowed));
            $data['fornissure_id'] = $user->id; // always enforce owner
        }

        return $data;
    }


    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        if (
            $user->hasRole('admin')
            || ($user->hasRole('operator') && ! $this->record->invoices()->exists())
        ) {
            return [
                Actions\DeleteAction::make(),
            ];
        }
        return []; // no delete button
    }
}
