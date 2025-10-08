@php
    $canCreateRevenu = \App\Filament\Resources\RevenuResource::canCreate();
@endphp

<div class="p-3 mb-4 rounded-md bg-red-100 border border-red-300 text-red-800 text-sm font-medium">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <span>⚠️ {{ __('resources.messages.missing_previous', ['date' => $missingDate]) }}</span>

        @if ($canCreateRevenu)
            <a
                href="{{ \App\Filament\Resources\RevenuResource::getUrl('create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            >
                <span aria-hidden="true">+</span>
                {{ __('resources.actions.add_revenue') }}
            </a>
        @endif
    </div>
</div>
