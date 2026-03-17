# Feature #64 — Restaurant/NaturalStore Types + Preferred Price

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add two missing customer types from the PoC (`restaurante`, `tienda_naturista`) and a preferred_price field for per-customer negotiated pricing. This enables differentiated pricing for restaurant and natural store customers.

## Scope
- Add `Restaurant` and `NaturalStore` cases to CustomerType enum
- Add `preferred_price` decimal column to customers
- Update CustomerResource form with preferred_price field
- Auto-fill unit_price from customer.preferred_price in OrderResource

## Implementation Details
- **Enum:** Add `Restaurant` (value: 'restaurant', label: 'Restaurante', color: 'success', icon: 'heroicon-o-building-storefront') and `NaturalStore` (value: 'natural_store', label: 'Tienda Naturista', color: 'info', icon: 'heroicon-o-heart') to `CustomerType`
- **Migration:** Add `preferred_price` (decimal 10,2, nullable) to `customers`
- **Model:** Add `preferred_price` to Customer fillable and casts (`decimal:2`)
- **CustomerResource:** Add TextInput for `preferred_price` (numeric, prefix '$', nullable)
- **OrderResource:** When customer is selected and has preferred_price, auto-fill unit_price on order lines

## Files Changed
- `app/Enums/CustomerType.php`
- `database/migrations/xxxx_add_preferred_price_to_customers_table.php`
- `app/Models/Customer.php`
- `app/Filament/Resources/CustomerResource.php`
- `app/Filament/Resources/OrderResource.php`

## Verification
- Verify Restaurant and NaturalStore options appear in customer type dropdown
- Set a preferred_price on a customer and verify it saves/displays
- Create an order for a customer with preferred_price and verify auto-fill
- Run `composer test` and `composer analyse` with no errors
