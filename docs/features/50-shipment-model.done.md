# Feature #50 — Add Shipment Model and Resource

**Priority:** HIGH
**Status:** Done
**Depends on:** #48
**Phase:** 2

## Summary
Shipment tracking for egg deliveries, linking orders to delivery logistics with status transitions from pending through delivered or cancelled.

## Scope
- Shipment model with status workflow
- ShipmentStatus enum with transition logic
- Filament resource with status transition actions
- Extended Order model with shipment reference and delivery date

## Implementation Details
- **Model:** `Shipment` (customer_id, driver, vehicle, route, status, scheduled_date, delivered_date, notes)
- **Enum:** `ShipmentStatus` (pending, in_transit, delivered, cancelled) with allowed transitions
- **Migration:** `create_shipments_table`, `add_shipment_fields_to_orders_table` (shipment_id FK, delivery_date)
- **Resource:** `ShipmentResource` with status badge column, transition actions (mark in transit, mark delivered, cancel)
- **Order model:** Added `shipment_id` (nullable FK) and `delivery_date` fields

## Files Changed
- `app/Models/Shipment.php`
- `app/Enums/ShipmentStatus.php`
- `database/migrations/xxxx_create_shipments_table.php`
- `database/migrations/xxxx_add_shipment_fields_to_orders_table.php`
- `app/Models/Order.php`
- `app/Filament/Resources/ShipmentResource.php`
- `app/Filament/Resources/ShipmentResource/Pages/`

## Verification
- Create a shipment and transition it through statuses (pending -> in_transit -> delivered)
- Verify invalid transitions are blocked
- Link an order to a shipment and confirm the relationship
- Run `composer test` and `composer analyse` with no errors
