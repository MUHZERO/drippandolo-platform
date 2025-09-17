<?php

namespace App\Providers;

use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;
use App\Models\FornissureInvoice;
use App\Observers\FornissureInvoiceObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add language support for the application
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn(): \Illuminate\View\View => view('filament.lang-switch.language-switcher'),
        );


        // Register Observer
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        FornissureInvoice::observe(FornissureInvoiceObserver::class);
    }
}
