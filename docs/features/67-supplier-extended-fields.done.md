# Feature #67 — Supplier Extended Fields

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Extend the Supplier model with address, products list, rating, and a tri-state status enum replacing the simple `is_active` boolean. Aligns with the PoC's richer supplier profiles.

## Scope
- New `SupplierStatus` enum replacing `is_active` boolean
- Add address, products (JSON), rating fields to suppliers
- Update SupplierResource form and table with new fields
- Backfill existing data: is_active=true → Active, false → Inactive

## Implementation Details
- **Enum:** `SupplierStatus` — Active (value: 'active', label: 'Activo', color: 'success'), Inactive (value: 'inactive', label: 'Inactivo', color: 'gray'), Suspended (value: 'suspended', label: 'Suspendido', color: 'danger'). Add icon() and options() methods
- **Migration:** Add `address` (text, nullable), `products` (jsonb, nullable), `rating` (integer, nullable), `status` (string, default 'active') to `suppliers`. Backfill: `UPDATE suppliers SET status = CASE WHEN is_active THEN 'active' ELSE 'inactive' END`. Drop `is_active` column
- **Model:** Replace `is_active` with `status` in fillable/casts (cast to SupplierStatus enum). Update `scopeActive()` to filter by `status = 'active'`. Add `products` to casts as `array`, `rating` to casts as `integer`
- **Resource form:** Add Textarea for `address`, TagsInput for `products` (suggestions from common farm supplies), Select for `rating` (1-5 stars), Select for `status` (SupplierStatus::class). Remove `is_active` Toggle
- **Resource table:** Add `status` badge column with enum color, `rating` column with star display

## Files Changed
- `app/Enums/SupplierStatus.php`
- `database/migrations/xxxx_extend_suppliers_table.php`
- `app/Models/Supplier.php`
- `app/Filament/Resources/SupplierResource.php`

## Verification
- Create a supplier with all new fields and verify they save correctly
- Verify existing suppliers are backfilled with correct status
- Test status filter replaces is_active filter
- Verify rating displays as expected in table
- Run `composer test` and `composer analyse` with no errors
