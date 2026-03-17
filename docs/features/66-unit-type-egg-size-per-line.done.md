# Feature #66 — Unit Type Enum + Egg Size per Line

**Priority:** HIGH
**Status:** Pending
**Depends on:** #65
**Phase:** 5 — Batch 2

## Summary
Add per-line egg size and unit type to order lines, enabling orders with mixed egg sizes and units (trays of 30, kilograms, individual pieces). Also adds a `Mixed` case to the EggSize enum.

## Scope
- New `UnitType` enum with egg-count conversion factors
- Add `Mixed` case to EggSize enum
- Add `egg_size` and `unit_type` columns to order_lines
- Update OrderResource items repeater with new selects

## Implementation Details
- **Enum:** `UnitType` — Tray (value: 'tray', label: 'Rejilla (30)', eggs: 30), Kilogram (value: 'kilogram', label: 'Kilogramo (~16)', eggs: 16), Piece (value: 'piece', label: 'Pieza', eggs: 1). Add `toEggs(): int` method for conversion
- **Enum:** Add `Mixed` case to `EggSize` (value: 'mixed', label: 'Mixto', color: 'gray')
- **Migration:** Add `egg_size` (string, nullable) and `unit_type` (string, nullable) to `order_lines`
- **Model:** Add to OrderLine fillable, cast `egg_size` to `EggSize` enum, cast `unit_type` to `UnitType` enum
- **Resource:** In OrderResource items repeater, add `egg_size` Select (EggSize::class options) and `unit_type` Select (UnitType::class options) per line item

## Files Changed
- `app/Enums/UnitType.php`
- `app/Enums/EggSize.php`
- `database/migrations/xxxx_add_egg_fields_to_order_lines_table.php`
- `app/Models/OrderLine.php`
- `app/Filament/Resources/OrderResource.php`

## Verification
- Create an order with different egg sizes and unit types per line
- Verify Mixed appears in EggSize options
- Verify UnitType conversion factors: Tray=30, Kilogram=16, Piece=1
- Run `composer test` and `composer analyse` with no errors
