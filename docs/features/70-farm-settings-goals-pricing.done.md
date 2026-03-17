# Feature #70 — Farm Settings (Goals + Pricing)

**Priority:** HIGH
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Extend the TenantSettings page with farm-specific configuration: production goals, pricing defaults, and operational settings. The PoC has `dailyEggGoal`, `monthlyIncomeGoal`, `trayPrice`, `kgPrice` that are missing from production.

## Scope
- Add "Farm Configuration" section to TenantSettings page
- Store farm settings in Tenant JSON settings field
- Update StatsOverview widget to show goal % indicators

## Implementation Details
- **Settings keys** (stored in tenant.settings JSON): `daily_egg_goal` (integer), `monthly_income_goal` (decimal), `tray_price` (decimal), `kg_price` (decimal), `standard_shipping_cost` (decimal), `responsible_name` (string), `municipality` (string)
- **TenantSettings page:** Add new "Farm Configuration" section with TextInputs for: daily_egg_goal (numeric, suffix 'eggs/day'), monthly_income_goal (numeric, prefix '$'), tray_price (numeric, prefix '$'), kg_price (numeric, prefix '$'), standard_shipping_cost (numeric, prefix '$'), responsible_name (text), municipality (text)
- **TenantSettings view:** Update Blade view to include the new section
- **StatsOverview widget:** On "Today's Production" stat, if daily_egg_goal is set, show description with "X% of daily goal". On "Monthly Revenue" stat, if monthly_income_goal is set, show description with "X% of monthly goal"

## Files Changed
- `app/Filament/Pages/TenantSettings.php`
- `resources/views/filament/pages/tenant-settings.blade.php`
- `app/Filament/Widgets/StatsOverview.php`

## Verification
- Navigate to Tenant Settings and verify Farm Configuration section appears
- Set daily_egg_goal and verify StatsOverview shows percentage
- Set monthly_income_goal and verify revenue stat shows percentage
- Verify all settings persist after save and page reload
- Run `composer test` and `composer analyse` with no errors
