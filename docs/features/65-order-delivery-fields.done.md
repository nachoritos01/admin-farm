# Feature #65 — Order Delivery Fields

**Priority:** HIGH
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add delivery logistics fields to orders, aligning with the PoC's delivery tracking. Currently orders only have `delivery_date` — this adds delivery type (pickup vs delivery), delivery time window, delivery notes, and shipping cost.

## Scope
- New `DeliveryType` enum (Pickup, Delivery)
- Add 4 delivery fields to orders table
- Update Order model totals to include shipping_cost
- Add Delivery section to OrderResource form

## Implementation Details
- **Enum:** `DeliveryType` — Pickup (value: 'pickup', label: 'Recoger en granja'), Delivery (value: 'delivery', label: 'Entrega a domicilio') with color() and icon() methods
- **Migration:** Add to `orders`: `delivery_type` (string, nullable), `delivery_time` (string, nullable), `delivery_notes` (text, nullable), `shipping_cost` (decimal 10,2, default 0)
- **Model:** Add fields to Order fillable, cast `delivery_type` to `DeliveryType` enum, cast `shipping_cost` to `decimal:2`. Update `recalculateTotals()` to add shipping_cost to total: `total = subtotal - discount_amount + tax + shipping_cost`
- **Resource:** Add "Delivery" section in OrderResource form with: delivery_type Select, delivery_date DatePicker (existing), delivery_time TextInput (placeholder "e.g. 8:00-10:00 AM"), delivery_notes Textarea, shipping_cost TextInput (numeric, prefix '$'). Show delivery fields conditionally when delivery_type is 'delivery'

## Files Changed
- `app/Enums/DeliveryType.php`
- `database/migrations/xxxx_add_delivery_fields_to_orders_table.php`
- `app/Models/Order.php`
- `app/Filament/Resources/OrderResource.php`

## Verification
- Create an order with Delivery type and verify all delivery fields save
- Create a Pickup order and verify delivery-specific fields are hidden
- Verify shipping_cost is included in total calculation
- Run `composer test` and `composer analyse` with no errors
