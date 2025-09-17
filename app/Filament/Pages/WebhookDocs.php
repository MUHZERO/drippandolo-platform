<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class WebhookDocs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.webhook-docs';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.webhooks.docs.title');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasRole('admin') ?? false;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user?->hasRole('admin') ?? false;
    }

    public function getTitle(): string
    {
        return __('resources.webhooks.docs.title');
    }
}

