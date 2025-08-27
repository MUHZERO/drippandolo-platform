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
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $lastWorkingDay = DateHelper::lastWorkingDay()->toDateString();
        $missingYesterday = ! Revenu::whereDate('date', $lastWorkingDay)->exists() && Revenu::count() > 0;

        if ($missingYesterday) {
            return view('filament.components.missing-revenue-alert', [
                'lastWorkingDay' => $lastWorkingDay,
            ]);
        }

        return parent::getHeader();
    }
}

