# Feature #53 — Add Egg Inventory Service and Page

**Priority:** HIGH
**Status:** Pending
**Depends on:** #47
**Phase:** 3

## Summary
Centralized inventory service that calculates current egg stock from production records minus sold and broken quantities, with a dedicated Filament page showing inventory levels and low-stock alerts.

## Scope
- InventoryService class with stock calculation logic
- Stock formula: production - sold - broken
- Filament InventoryPage with real-time stock display
- Low-stock alert thresholds and notifications
- Cached calculations for performance

## Implementation Details
- **Service:** `App\Services\InventoryService` with methods: `getCurrentStock()`, `getStockBySize()`, `getStockHistory()`, `isLowStock()`
- **Page:** `App\Filament\Pages\InventoryPage` with stock table, low-stock alerts banner, restock suggestions
- **Cache:** Stock calculations cached with 10-minute TTL, invalidated on production record or order changes
- **Alerts:** Configurable low-stock thresholds per egg size in tenant settings
- **Events:** Listen to ProductionRecord created/updated and Order completed to bust cache

## Files Changed
- `app/Services/InventoryService.php` (new)
- `app/Filament/Pages/InventoryPage.php` (new)
- `app/Listeners/InvalidateInventoryCache.php` (new)
- `app/Events/` (updated event subscriptions)

## Verification
- Add production records and verify stock calculation matches expected totals
- Create orders and verify sold quantities are subtracted from stock
- Verify low-stock alerts appear when stock drops below threshold
- Confirm cache invalidation works when new records are added
- Run `composer test` and `composer analyse` with no errors
