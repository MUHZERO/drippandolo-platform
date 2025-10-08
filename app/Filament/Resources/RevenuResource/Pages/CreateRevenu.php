<?php

namespace App\Filament\Resources\RevenuResource\Pages;

use App\Filament\Resources\RevenuResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Revenu;
use App\Helpers\DateHelper;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CreateRevenu extends CreateRecord
{
    protected static string $resource = RevenuResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selectedDate = Carbon::parse($data['date'])->startOfDay();

        if (DateHelper::isWeekend($selectedDate)) {
            throw ValidationException::withMessages([
                'date' => __('resources.messages.weekend_not_allowed'),
            ]);
        }

        if (! Revenu::query()->exists()) {
            $data['date'] = $selectedDate->toDateString();

            return $data;
        }

        $referenceDate = DateHelper::lastWorkingDay();
        $missingDate = Revenu::firstMissingWorkingDay($referenceDate);

        if ($missingDate && ! $selectedDate->equalTo($missingDate)) {
            throw ValidationException::withMessages([
                'date' => __('resources.messages.missing_previous', [
                    'date' => $missingDate->toDateString(),
                ]),
            ]);
        }

        if (! $missingDate) {
            $requiredDate = Revenu::nextFillableWorkingDay();

            if (! $selectedDate->equalTo($requiredDate)) {
                throw ValidationException::withMessages([
                    'date' => __('resources.messages.missing_previous', [
                        'date' => $requiredDate->toDateString(),
                    ]),
                ]);
            }
        }

        $data['date'] = $selectedDate->toDateString();

        return $data;
    }
}
