# Feature #58 — Farm Roles, Permissions, and Seeders

**Priority:** HIGH
**Status:** Pending
**Depends on:** All previous features
**Phase:** 3

## Summary
Update the RBAC system with 15 new farm-specific permissions, add a chofer (driver) role, register 6 new farm navigation plugins, update SaaS plan limits, and create a farm sample data seeder for demo environments.

## Scope
- Update RolesAndPermissionsSeeder with 15 new permissions for farm resources
- Add chofer role with shipment-specific permissions
- Update PluginSeeder with 6 new farm navigation plugins
- Update config/saas.php plan limits for farm features
- FarmSampleDataSeeder with realistic demo data

## Implementation Details
- **New permissions (15):** view/create/update/delete for hen_batches, production_records, shipments, expenses, suppliers, health_records; plus view_inventory, view_reports, view_accounts_receivable
- **New role:** `chofer` with permissions: view_shipments, update_shipments, view_customers
- **New plugins (6):** Hen Batches, Production Records, Shipments, Expenses, Suppliers, Inventory
- **Config update:** `config/saas.php` plan limits for max_batches, max_suppliers per plan tier
- **Seeder:** `FarmSampleDataSeeder` creating sample batches, production records, suppliers, expenses, shipments with realistic egg farm data
- All seeders must be idempotent (firstOrCreate/updateOrCreate)

## Files Changed
- `database/seeders/RolesAndPermissionsSeeder.php` (updated)
- `database/seeders/PluginSeeder.php` (updated)
- `database/seeders/FarmSampleDataSeeder.php` (new)
- `database/seeders/DatabaseSeeder.php` (register new seeder)
- `config/saas.php` (updated plan limits)

## Verification
- Run `php artisan db:seed` and verify idempotency (run twice without errors)
- Verify new permissions exist and are assigned to correct roles
- Verify chofer role can only access shipment-related resources
- Verify new plugins appear in plugin marketplace
- Verify FarmSampleDataSeeder creates realistic demo data
- Run `composer test` and `composer analyse` with no errors
