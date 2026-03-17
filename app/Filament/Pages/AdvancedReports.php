<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\HenBatch;
use App\Models\ProductionRecord;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdvancedReports extends Page
{
    protected static ?string $title = 'Advanced Reports';

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 60;

    protected static string $view = 'filament.pages.advanced-reports';

    public string $period = 'month';

    public Collection $productionByBatch;

    public Collection $expensesByCategory;

    public Collection $topCustomers;

    public array $monthlyComparison = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('reports.export') ?? false;
    }

    public function mount(): void
    {
        $this->loadReports();
    }

    public function loadReports(): void
    {
        $tenantId = currentTenant()?->id;

        if (! $tenantId) {
            $this->productionByBatch = collect();
            $this->expensesByCategory = collect();
            $this->topCustomers = collect();

            return;
        }

        $from = match ($this->period) {
            'week' => now()->startOfWeek()->toDateString(),
            'month' => now()->startOfMonth()->toDateString(),
            'quarter' => now()->startOfQuarter()->toDateString(),
            'year' => now()->startOfYear()->toDateString(),
            default => now()->startOfMonth()->toDateString(),
        };
        $to = now()->toDateString();

        // Production by batch
        $this->productionByBatch = ProductionRecord::where('tenant_id', $tenantId)
            ->whereBetween('date', [$from, $to])
            ->select('hen_batch_id', DB::raw('SUM(net_production) as total_production'), DB::raw('SUM(broken) as total_broken'), DB::raw('COUNT(*) as days'))
            ->groupBy('hen_batch_id')
            ->get()
            ->map(function ($row) {
                $batch = HenBatch::find($row->hen_batch_id);

                return [
                    'batch' => $batch?->name ?? 'Unknown',
                    'production' => $row->total_production,
                    'broken' => $row->total_broken,
                    'days' => $row->days,
                    'avg_daily' => $row->days > 0 ? round($row->total_production / $row->days) : 0,
                ];
            });

        // Expenses by category
        $this->expensesByCategory = Expense::where('tenant_id', $tenantId)
            ->whereBetween('date', [$from, $to])
            ->select('category', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Top customers by revenue
        $this->topCustomers = Customer::where('customers.tenant_id', $tenantId)
            ->withSum(['orders as total_revenue' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to . ' 23:59:59'])
                    ->whereNotIn('status', ['cancelled', 'draft']);
            }], 'total')
            ->get()
            ->filter(fn ($c) => ($c->total_revenue ?? 0) > 0)
            ->sortByDesc('total_revenue')
            ->take(10)
            ->map(fn ($c) => [
                'name' => $c->name,
                'type' => $c->customer_type->label(),
                'zone' => $c->zone,
                'revenue' => $c->total_revenue ?? 0,
            ])
            ->values();

        // Monthly comparison (current vs previous)
        $currentMonthStart = now()->startOfMonth()->toDateString();
        $currentMonthEnd = now()->endOfMonth()->toDateString();
        $prevMonthStart = now()->subMonth()->startOfMonth()->toDateString();
        $prevMonthEnd = now()->subMonth()->endOfMonth()->toDateString();

        $currentProduction = (int) ProductionRecord::where('tenant_id', $tenantId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('net_production');

        $prevProduction = (int) ProductionRecord::where('tenant_id', $tenantId)
            ->whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->sum('net_production');

        $currentExpenses = (float) Expense::where('tenant_id', $tenantId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $prevExpenses = (float) Expense::where('tenant_id', $tenantId)
            ->whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->sum('amount');

        $this->monthlyComparison = [
            'current_production' => $currentProduction,
            'prev_production' => $prevProduction,
            'production_change' => $prevProduction > 0
                ? round((($currentProduction - $prevProduction) / $prevProduction) * 100, 1)
                : 0,
            'current_expenses' => $currentExpenses,
            'prev_expenses' => $prevExpenses,
            'expenses_change' => $prevExpenses > 0
                ? round((($currentExpenses - $prevExpenses) / $prevExpenses) * 100, 1)
                : 0,
        ];
    }

    public function updatedPeriod(): void
    {
        $this->loadReports();
    }
}
