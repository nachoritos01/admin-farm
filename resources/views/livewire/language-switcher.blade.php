<div>
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <button type="button" class="flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5">
                <x-heroicon-m-language class="h-5 w-5" />
                <span>{{ strtoupper($locale) }}</span>
                <x-heroicon-m-chevron-down class="h-4 w-4" />
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item
                wire:click="switchLocale('es')"
                :icon="$locale === 'es' ? 'heroicon-m-check' : 'heroicon-m-language'"
                :color="$locale === 'es' ? 'primary' : 'gray'"
            >
                Español
            </x-filament::dropdown.list.item>

            <x-filament::dropdown.list.item
                wire:click="switchLocale('en')"
                :icon="$locale === 'en' ? 'heroicon-m-check' : 'heroicon-m-language'"
                :color="$locale === 'en' ? 'primary' : 'gray'"
            >
                English
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
