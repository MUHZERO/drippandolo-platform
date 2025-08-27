<?php

namespace App\Filament\Resources\RevenuResource\Pages;

use App\Filament\Resources\RevenuResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Revenu;
use App\Helpers\DateHelper;
use Illuminate\Validation\ValidationException;

class CreateRevenu extends CreateRecord
{
    protected static string $resource = RevenuResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lastWorkingDay = DateHelper::lastWorkingDay()->toDateString();

        if (
            $data['date'] == now()->toDateString() &&
            ! Revenu::whereDate('date', $lastWorkingDay)->exists() && Revenu::count() > 0
        ) {
            throw ValidationException::withMessages([
                'date' => __('resources.messages.missing_previous', [
                    'date' => $lastWorkingDay,
                ]),
            ]);
        }

        return $data;
    }
}

