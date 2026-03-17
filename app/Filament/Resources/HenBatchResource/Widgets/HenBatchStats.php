<?php

namespace App\Filament\Resources\HenBatchResource\Widgets;

use App\Models\HenBatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HenBatchStats extends BaseWidget
{
    protected function getStats(): array
    {
        $tenantId = currentTenant()?->id;

        if (! $tenantId) {
            return [];
        }

        $totalHens = HenBatch::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->sum('current_count');

        $activeBatches = HenBatch::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->count();

        $avgAge = HenBatch::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->avg('age_weeks');

        return [
            Stat::make(__('Total Hens'), number_format($totalHens))
                ->icon('heroicon-o-bug-ant')
                ->color('success'),
            Stat::make(__('Active Batches'), $activeBatches)
                ->icon('heroicon-o-rectangle-stack')
                ->color('info'),
            Stat::make(__('Avg Age (weeks)'), $avgAge ? number_format($avgAge, 1) : '0')
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
