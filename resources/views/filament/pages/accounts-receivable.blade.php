<x-filament-panels::page>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Outstanding Balance</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total amount pending collection</p>
            </div>
            <p class="text-3xl font-bold text-danger-600">${{ number_format($totalOutstanding, 2) }}</p>
        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Phone</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Type</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Total Billed</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Total Paid</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($debtors as $debtor)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $debtor->name }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $debtor->phone }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                    {{ $debtor->customer_type->value === 'wholesale' ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20' }}">
                                    {{ $debtor->customer_type->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">${{ number_format($debtor->total_debt, 2) }}</td>
                            <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">${{ number_format($debtor->total_paid, 2) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-danger-600">${{ number_format($debtor->balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No outstanding balances. All accounts are current.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($debtors->count() > 0)
                    <tfoot>
                        <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                            <td colspan="5" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Total Outstanding:</td>
                            <td class="px-4 py-3 text-right text-lg font-bold text-danger-600">${{ number_format($totalOutstanding, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
