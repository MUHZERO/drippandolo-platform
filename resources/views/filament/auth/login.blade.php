<x-filament-panels::page.simple>
   <nav aria-label="Language switcher" class="flex justify-center mb-4">
        <div class="inline-flex items-center space-x-1 p-1 bg-gray-100 dark:bg-gray-800 rounded-lg" role="tablist">
            @foreach(['en' => '🇬🇧 EN', 'it' => '🇮🇹 IT'] as $locale => $label)
                <a
                    href="{{ route('language.switch', $locale) }}"
                    role="tab"
                    aria-selected="{{ app()->getLocale() === $locale ? 'true' : 'false' }}"
                    aria-controls="language-content"
                    class="flex items-center px-3 py-1.5 text-sm font-medium rounded-md transition-colors
                           {{ app()->getLocale() === $locale
                               ? 'bg-primary-600 text-white shadow-sm'
                               : 'text-gray-700 dark:text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </nav>

    {{ $this->form }}
    <x-filament::button type="submit" wire:click="authenticate" class="w-full mt-4">
        {{ __('auth.login') }}
    </x-filament::button>

</x-filament-panels::page.simple>

