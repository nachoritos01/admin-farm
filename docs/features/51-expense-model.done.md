# Feature #51 — Add Expense Model and Resource

**Priority:** HIGH
**Status:** Done
**Depends on:** #49
**Phase:** 2

## Summary
Expense tracking for farm operations, categorized by type (feed, veterinary, transport, etc.) and linked to suppliers. Reuses the existing PaymentMethod enum.

## Scope
- Expense model with category and supplier association
- ExpenseCategory enum for farm-specific expense types
- Filament resource with supplier and category filters
- Restored expenses relationship on Supplier model

## Implementation Details
- **Model:** `Expense` (supplier_id, category, description, amount, date, payment_method, reference, notes)
- **Enum:** `ExpenseCategory` (feed, veterinary, packaging, transport, labor, utilities, maintenance, other)
- **Reused:** `PaymentMethod` enum for payment_method field
- **Migration:** `create_expenses_table` with supplier_id FK (nullable)
- **Resource:** `ExpenseResource` with supplier select, category filter, date range filter, sum aggregation
- **Relationship:** Added `expenses()` hasMany on Supplier model

## Files Changed
- `app/Models/Expense.php`
- `app/Enums/ExpenseCategory.php`
- `database/migrations/xxxx_create_expenses_table.php`
- `app/Filament/Resources/ExpenseResource.php`
- `app/Filament/Resources/ExpenseResource/Pages/`
- `app/Models/Supplier.php` (added expenses relationship)

## Verification
- Create expenses with and without supplier association
- Filter by category and supplier in the table
- Verify the expense total aggregation displays correctly
- Check Supplier detail page shows related expenses
- Run `composer test` and `composer analyse` with no errors
