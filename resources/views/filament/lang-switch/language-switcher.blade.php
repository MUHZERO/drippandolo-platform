<div class="relative px-3 py-2">
    <x-filament::dropdown placement="top-start">
        <x-slot name="trigger">
            <x-filament::button
                size="sm"
                color="gray"
                icon="heroicon-m-language"
            >
                {{ strtoupper(app()->getLocale() ?? 'en') }}
            </x-filament::button>
        </x-slot>

        {{-- Languages --}}
        <x-filament::dropdown.list>
            @foreach ([
                'en' => '🇬🇧 English',
                'it' => '🇮🇹 Italiano',
            ] as $locale => $label)
                <x-filament::dropdown.list.item
                    tag="a"
                    :href="route('language.switch', $locale)"
                    :active="app()->getLocale() === $locale"
                >
                    {{ $label }}
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>

