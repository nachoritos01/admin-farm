<?php

namespace App\Services;

use App\Models\Item;
use App\Models\OrderLine;
use App\Models\ProductionRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getStockLevels(?int $tenantId = null): Collection
    {
        $tenantId = $tenantId ?? currentTenant()?->id;

        if (! $tenantId) {
            return collect();
        }

        $ttl = config('saas.cache.dashboard_stats_ttl', 60);

        return Cache::remember("inventory_stock_{$tenantId}", $ttl, function () use ($tenantId) {
            $produced = ProductionRecord::where('tenant_id', $tenantId)
                ->sum('net_production');

            $sold = (int) OrderLine::whereHas('order', function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)
                    ->whereNotIn('status', ['cancelled', 'draft']);
            })->sum('quantity');

            $items = Item::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->whereNotNull('egg_size')
                ->get();

            return $items->map(function (Item $item) use ($tenantId) {
                $itemSold = (int) OrderLine::where('item_id', $item->id)
                    ->whereHas('order', function ($q) use ($tenantId) {
                        $q->where('tenant_id', $tenantId)
                            ->whereNotIn('status', ['cancelled', 'draft']);
                    })->sum('quantity');

                return [
                    'item_id' => $item->id,
                    'name' => $item->name,
                    'egg_size' => $item->egg_size?->label(),
                    'sold' => $itemSold,
                    'price' => $item->price,
                ];
            });
        });
    }

    public function getTotalStock(?int $tenantId = null): array
    {
        $tenantId = $tenantId ?? currentTenant()?->id;

        if (! $tenantId) {
            return ['produced' => 0, 'sold' => 0, 'broken' => 0, 'available' => 0];
        }

        $ttl = config('saas.cache.dashboard_stats_ttl', 60);

        return Cache::remember("inventory_total_{$tenantId}", $ttl, function () use ($tenantId) {
            $produced = (int) ProductionRecord::where('tenant_id', $tenantId)
                ->sum('qty_total');

            $broken = (int) ProductionRecord::where('tenant_id', $tenantId)
                ->sum('broken');

            $sold = (int) DB::table('order_lines')
                ->join('orders', 'order_lines.order_id', '=', 'orders.id')
                ->where('orders.tenant_id', $tenantId)
                ->whereNotIn('orders.status', ['cancelled', 'draft'])
                ->sum('order_lines.quantity');

            $available = $produced - $broken - $sold;

            return [
                'produced' => $produced,
                'sold' => $sold,
                'broken' => $broken,
                'available' => max(0, $available),
            ];
        });
    }

    public function isLowStock(?int $tenantId = null, int $threshold = 100): bool
    {
        $stock = $this->getTotalStock($tenantId);

        return $stock['available'] < $threshold;
    }
}
