<?php

namespace App\Services;

use App\Enums\EggSize;
use App\Enums\UnitType;

class PricingService
{
    public function getEggPrice(EggSize $size, ?int $tenantId = null): ?float
    {
        $tenantId = $tenantId ?? currentTenant()?->id;

        if (! $tenantId) {
            return null;
        }

        $tenant = \App\Models\Tenant::find($tenantId);

        if (! $tenant) {
            return null;
        }

        $settings = $tenant->settings ?? [];
        $prices = $settings['egg_size_prices'] ?? [];

        return isset($prices[$size->value]) ? (float) $prices[$size->value] : null;
    }

    public function resolveLinePrice(
        ?EggSize $size,
        ?UnitType $unit,
        ?float $customerPreferredPrice,
        ?int $tenantId
    ): float {
        // Customer preferred price takes priority
        if ($customerPreferredPrice && $customerPreferredPrice > 0) {
            return $customerPreferredPrice;
        }

        // Try egg size price matrix
        if ($size) {
            $sizePrice = $this->getEggPrice($size, $tenantId);
            if ($sizePrice !== null) {
                return $sizePrice;
            }
        }

        // Fallback: tenant tray/kg prices
        $tenant = \App\Models\Tenant::find($tenantId ?? currentTenant()?->id);

        if (! $tenant) {
            return 0;
        }

        $settings = $tenant->settings ?? [];

        if ($unit === UnitType::Tray && ! empty($settings['tray_price'])) {
            return (float) $settings['tray_price'];
        }

        if ($unit === UnitType::Kilogram && ! empty($settings['kg_price'])) {
            return (float) $settings['kg_price'];
        }

        return 0;
    }
}
