<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function lastWorkingDay(): Carbon
    {
        return self::previousWorkingDay(now());
    }

    public static function previousWorkingDay(Carbon $date): Carbon
    {
        $previous = $date->copy()->subDay();

        while (self::isWeekend($previous)) {
            $previous->subDay();
        }

        return $previous;
    }

    public static function nextWorkingDay(Carbon $date): Carbon
    {
        $next = $date->copy()->addDay();

        while (self::isWeekend($next)) {
            $next->addDay();
        }

        return $next;
    }

    public static function isWeekend(Carbon $date): bool
    {
        return $date->isSaturday() || $date->isSunday();
    }
}
