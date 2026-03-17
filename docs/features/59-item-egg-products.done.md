# Feature #59 — Extend Item Model for Egg Products

**Priority:** HIGH
**Status:** Done
**Depends on:** #48
**Phase:** 2

## Summary
Extended the Item model with egg product-specific fields: unit of measure, egg size classification, egg quality grade, and wholesale pricing to support the farm's product catalog.

## Scope
- Added unit, egg_size, egg_quality, wholesale_price columns to items table
- EggSize enum for size classification
- Updated ItemResource form and table to include new fields

## Implementation Details
- **Enum:** `EggSize` (jumbo, extra_large, large, medium, small)
- **Migration:** `add_egg_fields_to_items_table` adding `unit` (string, nullable), `egg_size` (string, nullable), `egg_quality` (string, nullable), `wholesale_price` (integer, nullable)
- **Model changes:** Added new fields to `$fillable` and `$casts` on Item model; reused `QualityGrade` enum for egg_quality cast
- **Resource updates:** Added Select inputs for unit, egg_size, egg_quality; TextInput for wholesale_price; corresponding table columns with toggleable visibility

## Files Changed
- `app/Enums/EggSize.php`
- `database/migrations/xxxx_add_egg_fields_to_items_table.php`
- `app/Models/Item.php` (updated fillable, casts)
- `app/Filament/Resources/ItemResource.php` (updated form and table)

## Verification
- Edit an item and set egg_size, egg_quality, unit, and wholesale_price
- Verify the table displays the new columns
- Filter/sort by egg_size and confirm correct behavior
- Verify wholesale_price is stored in cents and displayed formatted
- Run `composer test` and `composer analyse` with no errors
