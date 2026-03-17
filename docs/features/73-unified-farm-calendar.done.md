# Feature #73 — Unified Farm Calendar

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** #65
**Phase:** 5 — Batch 3

## Summary
Add a unified calendar page that consolidates production records, orders, and shipments into a single visual timeline. The PoC has a calendar view that production is missing entirely.

## Scope
- New FarmCalendar Filament page with FullCalendar integration
- Three event sources color-coded by type
- Toggle filters for each event type
- Click navigation to source records

## Implementation Details
- **Page:** `app/Filament/Pages/FarmCalendar.php` — Filament page using `samdark/yii2-fullcalendar` or equivalent JS calendar (FullCalendar via CDN or Filament plugin `guava/calendar`)
- **Event sources:**
  - Production records (green): date → "Production: {qty_total} eggs — {batch_name}"
  - Orders (blue): delivery_date or created_at → "Order #{id}: {customer_name} — ${total}"
  - Shipments (orange): scheduled_date → "Shipment: {zone} — {driver_name}"
- **Filters:** Toggle checkboxes for Production, Orders, Shipments (all enabled by default)
- **Interactions:** Day click shows detail list, event click navigates to source resource edit page
- **View:** Custom Blade view with FullCalendar JS initialization
- **Plugin:** Register in PluginSeeder or gate with appropriate module check

## Files Changed
- `app/Filament/Pages/FarmCalendar.php`
- `resources/views/filament/pages/farm-calendar.blade.php`
- `database/seeders/PluginSeeder.php` (optional: register as module)

## Verification
- Navigate to Farm Calendar page and verify it loads
- Verify production records appear as green events
- Verify orders appear as blue events on their delivery dates
- Verify shipments appear as orange events
- Click an event and verify navigation to the correct resource
- Toggle filters and verify events hide/show accordingly
- Run `composer test` and `composer analyse` with no errors
