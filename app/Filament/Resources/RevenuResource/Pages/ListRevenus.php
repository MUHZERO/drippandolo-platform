<?php

namespace App\Filament\Resources\RevenuResource\Pages;

use App\Filament\Resources\RevenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Revenu;
use App\Helpers\DateHelper;
use Illuminate\Contracts\View\View;

class ListRevenus extends ListRecords
{
    protected static string $resource = RevenuResource::class;

    protected function getHeaderActions(): array
    {
        $referenceDate = DateHelper::lastWorkingDay();
        $missingDate = Revenu::firstMissingWorkingDay($referenceDate);

        if ($missingDate) {
            return [];
        }

        return [
            Actions\CreateAction::make()
                ->label(__('resources.actions.add_revenue'))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getHeader(): ?View
    {
        $referenceDate = DateHelper::lastWorkingDay();
        $missingDate = Revenu::firstMissingWorkingDay($referenceDate);

        if (! $missingDate) {
            return parent::getHeader();
        }

        return view('filament.components.missing-revenue-alert', [
            'missingDate' => $missingDate->toDateString(),
        ]);
    }
}
