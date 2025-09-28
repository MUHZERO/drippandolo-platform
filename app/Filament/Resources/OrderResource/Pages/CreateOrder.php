<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        if ($user->hasRole('operator')) {
            $data['operator_id']   = $user->id;
            $data['fornissure_id'] = User::whereHas('roles', fn($q) => $q->where('name', 'fornissure'))->value('id');
            unset($data['confirmation_price_id']); // ensure operator never sets it
            unset($data['tracking_number']);
        }

        if ($user->hasRole('fornissure')) {
            $data['fornissure_id'] = $user->id;

            // only allow status + confirmation_price_id on create
            $data = array_intersect_key($data, [
                'status' => true,
                'confirmation_price_id' => true,
                'fornissure_id' => true,
            ]);
        }
        return $data;
    }
}
