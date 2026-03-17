# Feature #55 — Add Advanced Reports Page

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** #47, #48, #51, #53
**Phase:** 4

## Summary
Comprehensive reporting page with profitability analysis by customer, margin calculations, production statistics by batch, monthly comparisons, and CSV export capabilities.

## Scope
- Profitability by customer report (revenue - cost of goods)
- Margin analysis per product/egg size
- Production by batch with efficiency metrics
- Monthly comparison tables (MoM growth)
- CSV export for all report types

## Implementation Details
- **Page:** `App\Filament\Pages\ReportsPage` with tabbed interface for each report type
- **Service:** `App\Services\ReportService` with methods per report type
- **Reports:**
  - Customer Profitability: revenue from orders, costs allocated, net margin
  - Margin Analysis: selling price vs production cost per egg size
  - Production by Batch: qty produced, mortality rate, feed efficiency
  - Monthly Comparison: side-by-side month columns with growth percentages
- **Export:** CSV export action on each report tab using Laravel Excel or stream download
- **Filters:** Date range, customer, batch, product selectors

## Files Changed
- `app/Filament/Pages/ReportsPage.php` (new)
- `app/Services/ReportService.php` (new)
- `app/Exports/` (new export classes per report type)

## Verification
- Navigate to Reports page and verify each tab loads data correctly
- Apply date range filters and confirm data updates
- Export CSV for each report type and verify file contents match displayed data
- Verify performance is acceptable with large datasets
- Run `composer test` and `composer analyse` with no errors
