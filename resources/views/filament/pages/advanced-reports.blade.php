<x-filament-panels::page>
    <div class="mb-4 flex items-center gap-4">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('farm.period') }}</label>
        <select wire:model.live="period" class="rounded-lg border-gray-300 text-sm shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <option value="week">{{ __('farm.this_week') }}</option>
            <option value="month">{{ __('farm.this_month') }}</option>
            <option value="quarter">{{ __('farm.this_quarter') }}</option>
            <option value="year">{{ __('farm.this_year') }}</option>
        </select>
    </div>

    {{-- Monthly Comparison --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.current_month_production') }}</p>
                <p class="text-2xl font-bold text-primary-600">{{ number_format($monthlyComparison['current_production'] ?? 0) }}</p>
                @if(($monthlyComparison['production_change'] ?? 0) != 0)
                    <p class="text-xs {{ ($monthlyComparison['production_change'] ?? 0) > 0 ? 'text-success-600' : 'text-danger-600' }}">
                        {{ ($monthlyComparison['production_change'] ?? 0) > 0 ? '+' : '' }}{{ $monthlyComparison['production_change'] ?? 0 }}% {{ __('farm.vs_last_month') }}
                    </p>
                @endif
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.previous_month_production') }}</p>
                <p class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ number_format($monthlyComparison['prev_production'] ?? 0) }}</p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.current_month_expenses') }}</p>
                <p class="text-2xl font-bold text-danger-600">${{ number_format($monthlyComparison['current_expenses'] ?? 0, 2) }}</p>
                @if(($monthlyComparison['expenses_change'] ?? 0) != 0)
                    <p class="text-xs {{ ($monthlyComparison['expenses_change'] ?? 0) < 0 ? 'text-success-600' : 'text-danger-600' }}">
                        {{ ($monthlyComparison['expenses_change'] ?? 0) > 0 ? '+' : '' }}{{ $monthlyComparison['expenses_change'] ?? 0 }}% {{ __('farm.vs_last_month') }}
                    </p>
                @endif
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('farm.previous_month_expenses') }}</p>
                <p class="text-2xl font-bold text-gray-600 dark:text-gray-300">${{ number_format($monthlyComparison['prev_expenses'] ?? 0, 2) }}</p>
            </div>
        </x-filament::section>
    </div>

    {{-- Production by Batch --}}
    <x-filament::section>
        <x-slot name="heading">{{ __('farm.production_by_batch') }}</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">{{ __('Batch') }}</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.total_eggs') }}</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.broken') }}</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.days') }}</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.avg_day') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($productionByBatch as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['batch'] }}</td>
                            <td class="px-4 py-3 text-right text-success-600 font-semibold">{{ number_format($row['production']) }}</td>
                            <td class="px-4 py-3 text-right text-danger-600">{{ number_format($row['broken']) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ $row['days'] }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ number_format($row['avg_daily']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">{{ __('farm.no_production_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Expenses by Category --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('farm.expenses_by_category') }}</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">{{ __('Category') }}</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.count') }}</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($expensesByCategory as $row)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ ucfirst($row->category) }}</td>
                                <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">{{ $row->count }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-danger-600">${{ number_format($row->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">{{ __('farm.no_expenses_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        {{-- Top Customers --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('farm.top_customers_revenue') }}</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ __('farm.revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topCustomers as $customer)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $customer['name'] }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $customer['type'] }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-success-600">${{ number_format($customer['revenue'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">{{ __('farm.no_revenue_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
