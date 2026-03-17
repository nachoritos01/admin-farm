# Feature #57 — Add Accounts Receivable Page

**Priority:** HIGH
**Status:** Pending
**Depends on:** #48
**Phase:** 3

## Summary
Accounts receivable management page showing outstanding customer balances, enabling payment recording, and providing printable customer statements for debt collection.

## Scope
- Outstanding balances page with customer debt summary
- Customer debt table with aging columns (current, 30, 60, 90+ days)
- Record payment action to apply payments against outstanding orders
- Printable customer statement (PDF or print-friendly view)

## Implementation Details
- **Page:** `App\Filament\Pages\AccountsReceivablePage` with customer debt table
- **Service:** `App\Services\AccountsReceivableService` with balance calculation, aging analysis
- **Actions:** Record payment action (customer select, amount, payment method, reference)
- **Payment model or pivot:** Track partial payments against orders
- **Statement:** Printable view with customer info, order history, payments, running balance
- **Aging buckets:** Current (0-30 days), 30-60, 60-90, 90+ days overdue

## Files Changed
- `app/Filament/Pages/AccountsReceivablePage.php` (new)
- `app/Services/AccountsReceivableService.php` (new)
- `app/Models/Payment.php` (new, or extend existing)
- `resources/views/statements/customer-statement.blade.php` (new)

## Verification
- Navigate to Accounts Receivable and verify customer balances are correct
- Record a partial payment and confirm balance updates
- Record full payment and confirm order marked as paid
- Generate a printable statement and verify formatting
- Verify aging columns show correct categorization
- Run `composer test` and `composer analyse` with no errors
