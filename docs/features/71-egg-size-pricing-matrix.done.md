# Feature #71 — Egg Size Pricing Matrix

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** #66, #70
**Phase:** 5 — Batch 3

## Summary
Add a centralized egg size pricing matrix stored in tenant settings, with auto-fill capability when creating order lines. The PoC has an `EGG_SIZE_PRICES` matrix that maps egg sizes to prices — production has no equivalent.

## Scope
- Store `egg_size_prices` matrix in tenant settings JSON
- Add pricing matrix UI section in TenantSettings
- New PricingService for centralized price resolution
- Auto-fill unit_price in OrderResource when egg_size + unit_type selected

## Implementation Details
- **Settings key:** `egg_size_prices` — JSON object mapping EggSize values to prices, e.g. `{"small": 45, "medium": 50, "large": 55, "extra_large": 60, "jumbo": 70, "mixed": 48}`
- **TenantSettings:** Add "Egg Price Matrix" section with a KeyValue or repeater for egg_size → price mappings. Pre-populate with EggSize enum values
- **PricingService:** New service `app/Services/PricingService.php` with methods: `getEggPrice(EggSize $size, ?int $tenantId = null): ?float` — reads from tenant settings. `resolveLinePrice(EggSize $size, UnitType $unit, ?float $customerPreferredPrice = null, ?int $tenantId = null): float` — priority: customer preferred_price > egg_size_prices matrix > item price
- **OrderResource:** When egg_size and unit_type are selected on a line item, auto-fill unit_price from PricingService. Use `->afterStateUpdated()` reactive callback

## Files Changed
- `app/Filament/Pages/TenantSettings.php`
- `resources/views/filament/pages/tenant-settings.blade.php`
- `app/Services/PricingService.php`
- `app/Filament/Resources/OrderResource.php`

## Verification
- Set egg size prices in TenantSettings and verify they persist
- Create an order line, select egg_size, and verify unit_price auto-fills
- Verify customer preferred_price takes priority over matrix price
- Run `composer test` and `composer analyse` with no errors
