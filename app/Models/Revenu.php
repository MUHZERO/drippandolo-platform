<?php

namespace App\Models;

use App\Helpers\DateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Revenu extends Model
{
    protected $fillable = [
        'date',
        'amount',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public static function recordedWorkingDatesUntil(Carbon $upToDate): Collection
    {
        $upToDate = $upToDate->copy()->startOfDay();

        return self::query()
            ->whereDate('date', '<=', $upToDate)
            ->orderBy('date')
            ->pluck('date')
            ->map(fn($value) => Carbon::parse($value)->startOfDay())
            ->unique(fn(Carbon $date) => $date->toDateString())
            ->values();
    }

    public static function firstMissingWorkingDay(?Carbon $upToDate = null): ?Carbon
    {
        $upToDate = $upToDate?->copy()->startOfDay() ?? now()->startOfDay();
        $dates = self::recordedWorkingDatesUntil($upToDate);

        if ($dates->isEmpty()) {
            return null;
        }

        $indexed = $dates
            ->mapWithKeys(fn(Carbon $date) => [$date->toDateString() => true]);

        $cursor = $dates->first()->copy();

        while ($cursor->lessThanOrEqualTo($upToDate)) {
            if (! DateHelper::isWeekend($cursor) && ! isset($indexed[$cursor->toDateString()])) {
                return $cursor->copy();
            }

            $cursor->addDay();
        }

        return null;
    }

    public static function nextFillableWorkingDay(?Carbon $upToDate = null): Carbon
    {
        $upToDate = $upToDate?->copy()->startOfDay() ?? now()->startOfDay();

        $latest = self::query()
            ->whereDate('date', '<=', $upToDate)
            ->max('date');

        if (! $latest) {
            $candidate = $upToDate->copy();

            if (DateHelper::isWeekend($candidate)) {
                return DateHelper::previousWorkingDay($candidate);
            }

            return $candidate;
        }

        return DateHelper::nextWorkingDay(Carbon::parse($latest)->startOfDay());
    }

}
