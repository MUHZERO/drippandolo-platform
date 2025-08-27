<?php

namespace App\Filament\Resources\SpendResource\Pages;

use App\Filament\Resources\SpendResource;
use App\Models\Spend;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateSpend extends CreateRecord
{
    protected static string $resource = SpendResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $query = Spend::where('type', $data['type']);

        if ($data['type'] === 'hosting') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        if ($query->exists()) {
            $this->addError('type', __("You already posted a {$data['type']} spend for this period."));
        }

        return $data;
    }
}

