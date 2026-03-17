# Feature #60 — Add Dirty Eggs to Production

**Priority:** HIGH
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add dirty eggs tracking to production records, aligning with the PoC's `dirtyEggs` field. Currently production only tracks `broken` eggs — dirty eggs are a separate loss category (unsellable but not broken) that must be subtracted from net production.

## Scope
- Add `dirty` integer column to `production_records` table
- Update net_production formula: `net_production = qty_total - broken - dirty`
- Update ProductionRecordResource form and table
- Update InventoryService stock calculation

## Implementation Details
- **Migration:** Add `dirty` (integer, default 0) to `production_records`
- **Model:** Update `saving()` boot event — `net_production = qty_total - broken - dirty`
- **Resource form:** Add `dirty` TextInput (numeric, minValue 0) after `broken` field
- **Resource table:** Add `dirty` column with `color('warning')` badge
- **InventoryService:** Update `getTotalStock()` to include dirty in subtraction: `available = produced - broken - dirty - sold`

## Files Changed
- `database/migrations/xxxx_add_dirty_to_production_records_table.php`
- `app/Models/ProductionRecord.php`
- `app/Filament/Resources/ProductionRecordResource.php`
- `app/Services/InventoryService.php`

## Verification
- Create a production record with dirty eggs and verify net_production = qty_total - broken - dirty
- Verify dirty column appears in table with warning color
- Check InventoryService stock levels subtract dirty eggs
- Run `composer test` and `composer analyse` with no errors
