<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function lastWorkingDay(): Carbon
    {
        $yesterday = now()->subDay();

        // If yesterday is Saturday → use Friday
        if ($yesterday->isSaturday()) {
            return $yesterday->subDay();
        }

        // If yesterday is Sunday → use Friday
        if ($yesterday->isSunday()) {
            return $yesterday->subDays(2);
        }

        return $yesterday;
    }
}

