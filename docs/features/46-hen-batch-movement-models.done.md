# Feature #46 — Add Hen Batch and Movement Models

**Priority:** HIGH
**Status:** Done
**Depends on:** None
**Phase:** 1

## Summary
Core poultry management models for tracking hen batches (flocks) and their movements (arrivals, transfers, deaths, cullings) throughout the farm.

## Scope
- HenBatch model with status tracking (active, depleted, culled)
- HenMovement model for recording all batch movements
- HenBatchStatus and HenMovementType enums
- Filament admin resource with relation manager and stats widget

## Implementation Details
- **Models:** `HenBatch` (breed, quantity, arrival_date, status, notes), `HenMovement` (hen_batch_id, type, quantity, date, notes)
- **Enums:** `HenBatchStatus` (active, depleted, culled), `HenMovementType` (arrival, transfer, death, culling)
- **Migrations:** `create_hen_batches_table`, `create_hen_movements_table`
- **Resource:** `HenBatchResource` with full CRUD, table filters by status
- **Relation Manager:** `MovementsRelationManager` on HenBatchResource
- **Widget:** `HenBatchStats` showing active batches, total hens, recent movements

## Files Changed
- `app/Models/HenBatch.php`
- `app/Models/HenMovement.php`
- `app/Enums/HenBatchStatus.php`
- `app/Enums/HenMovementType.php`
- `database/migrations/xxxx_create_hen_batches_table.php`
- `database/migrations/xxxx_create_hen_movements_table.php`
- `app/Filament/Resources/HenBatchResource.php`
- `app/Filament/Resources/HenBatchResource/Pages/`
- `app/Filament/Resources/HenBatchResource/RelationManagers/MovementsRelationManager.php`
- `app/Filament/Resources/HenBatchResource/Widgets/HenBatchStats.php`

## Verification
- Navigate to Admin > Hen Batches and verify CRUD operations
- Create a batch with movements and verify the relation manager displays them
- Confirm the stats widget shows correct counts on the resource page
- Run `composer test` and `composer analyse` with no errors
