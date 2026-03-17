<x-filament-panels::page>
    @if($isLowStock)
        <div class="rounded-lg bg-danger-50 p-4 dark:bg-danger-400/10">
            <div class="flex">
                <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-danger-500" />
                <div class="ml-3">
                    <p class="text-sm font-medium text-danger-800 dark:text-danger-200">
                        {{ __('farm.low_stock_alert') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.total_produced') }}</p>
                <p class="text-3xl font-bold text-primary-600">{{ number_format($stock['produced'] ?? 0) }}</p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.total_sold') }}</p>
                <p class="text-3xl font-bold text-success-600">{{ number_format($stock['sold'] ?? 0) }}</p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.broken_lost') }}</p>
                <p class="text-3xl font-bold text-danger-600">{{ number_format($stock['broken'] ?? 0) }}</p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.available_stock') }}</p>
                <p class="text-3xl font-bold {{ ($stock['available'] ?? 0) < 100 ? 'text-danger-600' : 'text-success-600' }}">
                    {{ number_format($stock['available'] ?? 0) }}
                </p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
