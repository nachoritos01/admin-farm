<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Expense;
use App\Models\HenBatch;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductionRecord;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = currentTenant();
        $tenantId = $tenant?->id;

        if (! $tenantId) {
            return [];
        }

        $settings = $tenant->settings ?? [];
        $dailyGoal = $settings['daily_egg_goal'] ?? null;
        $monthlyGoal = $settings['monthly_income_goal'] ?? null;

        $ttl = config('saas.cache.dashboard_stats_ttl', 60);

        $stats = Cache::remember("dashboard_stats_{$tenantId}", $ttl, function () use ($tenantId) {
            $today = now()->toDateString();
            $monthStart = now()->startOfMonth()->toDateString();
            $monthEnd = now()->endOfMonth()->toDateString();

            return [
                'today_production' => (int) ProductionRecord::where('tenant_id', $tenantId)
                    ->where('date', $today)
                    ->sum('net_production'),
                'month_production' => (int) ProductionRecord::where('tenant_id', $tenantId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('net_production'),
                'active_hens' => (int) HenBatch::where('tenant_id', $tenantId)
                    ->where('is_active', true)
                    ->sum('current_count'),
                'pending_orders' => Order::where('tenant_id', $tenantId)
                    ->whereIn('status', [OrderStatus::Pending, OrderStatus::Confirmed])
                    ->count(),
                'month_revenue' => (float) Payment::where('tenant_id', $tenantId)
                    ->whereMonth('received_at', now()->month)
                    ->whereYear('received_at', now()->year)
                    ->sum('amount'),
                'month_expenses' => (float) Expense::where('tenant_id', $tenantId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        });

        $todayProdStat = Stat::make(__('farm.todays_production'), number_format($stats['today_production']) . ' ' . __('farm.eggs'))
            ->icon('heroicon-o-chart-bar')
            ->color('success');
        if ($dailyGoal && $dailyGoal > 0) {
            $pct = round(($stats['today_production'] / $dailyGoal) * 100);
            $todayProdStat->description("{$pct}% " . __('farm.of_daily_goal'));
        }

        $monthRevStat = Stat::make(__('farm.monthly_revenue'), '$' . number_format($stats['month_revenue'], 2))
            ->icon('heroicon-o-currency-dollar')
            ->color('success');
        if ($monthlyGoal && $monthlyGoal > 0) {
            $pct = round(($stats['month_revenue'] / $monthlyGoal) * 100);
            $monthRevStat->description("{$pct}% " . __('farm.of_monthly_goal'));
        }

        return [
            $todayProdStat,
            Stat::make(__('Production') . ' ' . __('This Month'), number_format($stats['month_production']) . ' ' . __('farm.eggs'))
                ->icon('heroicon-o-calendar')
                ->color('info'),
            Stat::make(__('farm.active_batches'), number_format($stats['active_hens']))
                ->icon('heroicon-o-bug-ant')
                ->color('primary'),
            Stat::make(__('farm.pending_orders'), $stats['pending_orders'])
                ->icon('heroicon-o-shopping-bag')
                ->color($stats['pending_orders'] > 0 ? 'warning' : 'success'),
            $monthRevStat,
            Stat::make(__('Expenses') . ' ' . __('This Month'), '$' . number_format($stats['month_expenses'], 2))
                ->icon('heroicon-o-banknotes')
                ->color('danger'),
        ];
    }
}
