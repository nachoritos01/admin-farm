# Feature #72 — Cash on Delivery Payment

**Priority:** LOW
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add a `CashOnDelivery` (contra entrega) case to the PaymentMethod enum. The PoC has `contra_entrega` as a payment method — production currently has Cash, Card, Transfer, and Other but no explicit COD option.

## Scope
- Add `CashOnDelivery` case to existing `PaymentMethod` enum
- No migration needed (enum values stored as strings)

## Implementation Details
- **Enum:** Add `CashOnDelivery` case to `PaymentMethod` with value `'cash_on_delivery'`, label `'Contra entrega'`, color `'warning'`
- No model or migration changes required — the enum is cast from a string column

## Files Changed
- `app/Enums/PaymentMethod.php`

## Verification
- Verify CashOnDelivery option appears in payment method dropdowns (Payments, Expenses)
- Record a payment with CashOnDelivery method and verify it saves/displays
- Verify the warning color renders correctly in badges
- Run `composer test` and `composer analyse` with no errors
