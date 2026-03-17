# Feature #68 — Purchase Model

**Priority:** HIGH
**Status:** Pending
**Depends on:** #67
**Phase:** 5 — Batch 2

## Summary
Add a full Purchase model for tracking farm supply purchases from suppliers. The PoC has a complete purchase tracking system (quantity, unit, unit price, total) that is entirely missing from production.

## Scope
- New Purchase model with supplier relation
- New PurchaseUnit enum for measurement units
- New PurchaseResource with Filament CRUD
- PurchasesRelationManager on SupplierResource
- Register as new plugin module

## Implementation Details
- **Model:** `Purchase` — tenant_id, supplier_id (FK), product (string), quantity (decimal 10,2), unit (string), unit_price (decimal 10,2), total (decimal 10,2), date (date), notes (text), payment_method (string). Boot: auto-calculate `total = quantity * unit_price`. Traits: BelongsToTenant, HasFactory
- **Enum:** `PurchaseUnit` — Kilogram (value: 'kilogram', label: 'Kilogramo'), Liter (value: 'liter', label: 'Litro'), Piece (value: 'piece', label: 'Pieza'), Sack (value: 'sack', label: 'Saco/Bulto'), Ton (value: 'ton', label: 'Tonelada'), Other (value: 'other', label: 'Otro'). Add options() method
- **Migration:** `create_purchases_table` with all fields, FK to suppliers and tenants
- **Resource:** `PurchaseResource` with form sections: Purchase Info (supplier select, product, quantity, unit, unit_price, total read-only, date, payment_method), Notes. Table: date, supplier name, product, quantity+unit, total (money), payment_method badge
- **RelationManager:** `PurchasesRelationManager` on SupplierResource — shows purchases for that supplier
- **Plugin:** Add `purchases` plugin to PluginSeeder (free, all plans). Gate resource with `hasModule('purchases')`

## Files Changed
- `app/Models/Purchase.php`
- `app/Enums/PurchaseUnit.php`
- `database/migrations/xxxx_create_purchases_table.php`
- `app/Filament/Resources/PurchaseResource.php`
- `app/Filament/Resources/PurchaseResource/Pages/CreatePurchase.php`
- `app/Filament/Resources/PurchaseResource/Pages/EditPurchase.php`
- `app/Filament/Resources/PurchaseResource/Pages/ListPurchases.php`
- `app/Filament/Resources/SupplierResource.php` (add relation manager)
- `app/Filament/Resources/SupplierResource/RelationManagers/PurchasesRelationManager.php`
- `app/Models/Supplier.php` (add purchases() relation)
- `database/seeders/PluginSeeder.php`

## Verification
- Create a purchase and verify total auto-calculates
- View a supplier and verify PurchasesRelationManager shows their purchases
- Verify the purchases plugin appears in PluginSeeder
- Run `composer test` and `composer analyse` with no errors
