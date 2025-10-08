<?php

namespace App\Filament\Resources\RevenuResource\Pages;

use App\Filament\Resources\RevenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Revenu;
use App\Helpers\DateHelper;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EditRevenu extends EditRecord
{
    protected static string $resource = RevenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $selectedDate = Carbon::parse($data['date'])->startOfDay();

        if (DateHelper::isWeekend($selectedDate)) {
            throw ValidationException::withMessages([
                'date' => __('resources.messages.weekend_not_allowed'),
            ]);
        }

        $recordId = $this->record->getKey();

        $previousRecord = Revenu::query()
            ->where('id', '!=', $recordId)
            ->whereDate('date', '<', $selectedDate)
            ->orderBy('date', 'desc')
            ->first();

        if ($previousRecord) {
            $expectedDate = DateHelper::nextWorkingDay(Carbon::parse($previousRecord->date)->startOfDay());

            if (! $selectedDate->equalTo($expectedDate)) {
                throw ValidationException::withMessages([
                    'date' => __('resources.messages.missing_previous', [
                        'date' => $expectedDate->toDateString(),
                    ]),
                ]);
            }
        }

        $nextRecord = Revenu::query()
            ->where('id', '!=', $recordId)
            ->whereDate('date', '>', $selectedDate)
            ->orderBy('date')
            ->first();

        if ($nextRecord) {
            $expectedNext = DateHelper::nextWorkingDay($selectedDate);
            $nextDate = Carbon::parse($nextRecord->date)->startOfDay();

            if (! $nextDate->equalTo($expectedNext)) {
                throw ValidationException::withMessages([
                    'date' => __('resources.messages.missing_previous', [
                        'date' => $expectedNext->toDateString(),
                    ]),
                ]);
            }
        }

        $data['date'] = $selectedDate->toDateString();

        return $data;
    }
}
