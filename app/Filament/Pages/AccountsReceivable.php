<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class AccountsReceivable extends Page
{
    protected static ?string $title = 'Accounts Receivable';

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'Receivables';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 55;

    protected static string $view = 'filament.pages.accounts-receivable';

    public Collection $debtors;

    public float $totalOutstanding = 0;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('payments.view') ?? false;
    }

    public function mount(): void
    {
        $tenantId = currentTenant()?->id;

        $this->debtors = $tenantId
            ? Customer::where('tenant_id', $tenantId)
                ->whereHas('orders', function ($q) {
                    $q->whereColumn('total', '>', 'total_paid')
                        ->whereNotIn('status', ['cancelled', 'draft']);
                })
                ->withSum(['orders as total_debt' => function ($q) {
                    $q->whereNotIn('status', ['cancelled', 'draft']);
                }], 'total')
                ->withSum(['orders as total_paid' => function ($q) {
                    $q->whereNotIn('status', ['cancelled', 'draft']);
                }], 'total_paid')
                ->get()
                ->map(function (Customer $customer) {
                    $customer->balance = ($customer->total_debt ?? 0) - ($customer->total_paid ?? 0);

                    return $customer;
                })
                ->filter(fn ($c) => $c->balance > 0)
                ->sortByDesc('balance')
                ->values()
            : collect();

        $this->totalOutstanding = $this->debtors->sum('balance');
    }
}
