# Feature #69 — Shipment Vehicle + Route

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add vehicle and route fields to shipments, aligning with the PoC's delivery logistics tracking. Currently shipments only track zone — the vehicle identifier and route description are missing.

## Scope
- Add `vehicle` and `route` columns to shipments table
- Update Shipment model fillable
- Update ShipmentResource form and table

## Implementation Details
- **Migration:** Add `vehicle` (string, nullable) and `route` (text, nullable) to `shipments`
- **Model:** Add `vehicle` and `route` to Shipment fillable array
- **Resource form:** Add `vehicle` TextInput (placeholder "e.g. Camioneta Ford #3") after zone field, add `route` Textarea (placeholder "Describe the delivery route", rows 3) after vehicle
- **Resource table:** Add `vehicle` TextColumn (searchable, toggleable) after zone column

## Files Changed
- `database/migrations/xxxx_add_vehicle_route_to_shipments_table.php`
- `app/Models/Shipment.php`
- `app/Filament/Resources/ShipmentResource.php`

## Verification
- Create a shipment with vehicle and route and verify they save
- Verify vehicle appears in the table and is searchable
- Run `composer test` and `composer analyse` with no errors
