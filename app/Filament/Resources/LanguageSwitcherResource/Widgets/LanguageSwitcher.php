<?php

namespace App\Filament\Resources\LanguageSwitcherResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher extends Widget
{
   protected static string $view = 'filament.lang-switch.language-switcher';
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    public static function canView(): bool
    {
        return true;
    }
}

