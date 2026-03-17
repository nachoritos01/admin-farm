# Feature #52 — Extend Dashboard with Farm KPIs

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** #46, #47, #48, #51
**Phase:** 3

## Summary
Replace the generic SaaS dashboard statistics with farm-specific KPIs: daily/weekly production totals, current stock levels, pending orders, expense summaries, and active batch counts.

## Scope
- Replace generic stat widgets with farm KPI widgets
- Production totals (today, this week, this month)
- Current egg stock levels by size/quality
- Pending orders count and value
- ProductionChart widget (daily production over time)
- StockLevelWidget showing current inventory breakdown

## Implementation Details
- **Widgets:** `FarmStatsOverview` (replaces generic stats), `ProductionChart` (line/bar chart), `StockLevelWidget` (current inventory)
- **Data sources:** ProductionRecord (aggregated), Order (pending), Expense (period totals), HenBatch (active counts)
- **Cache:** Widget data cached for 5 minutes to reduce query load
- Conditional rendering: only show farm widgets when tenant has farm plugins enabled

## Files Changed
- `app/Filament/Widgets/FarmStatsOverview.php` (new)
- `app/Filament/Widgets/ProductionChart.php` (new)
- `app/Filament/Widgets/StockLevelWidget.php` (new)
- `app/Filament/Pages/Dashboard.php` (updated widget registration)

## Verification
- Log in as a farm tenant admin and verify dashboard shows farm KPIs
- Verify production chart renders with correct date range data
- Verify stock level widget shows breakdown by egg size
- Confirm non-farm tenants still see generic dashboard
- Run `composer test` and `composer analyse` with no errors
