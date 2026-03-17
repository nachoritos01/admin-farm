# Feature #56 — Add Hen Health Records

**Priority:** MEDIUM
**Status:** Done
**Depends on:** #46
**Phase:** 2

## Summary
Health record tracking for hen batches, supporting vaccinations, treatments, inspections, and other health events with upcoming and overdue scheduling scopes.

## Scope
- HenHealthRecord model linked to HenBatch
- HealthRecordType enum for classifying health events
- HealthRecordsRelationManager on HenBatchResource
- Upcoming and overdue query scopes for scheduling

## Implementation Details
- **Model:** `HenHealthRecord` (hen_batch_id, type, description, administered_by, date, next_due_date, cost, notes)
- **Enum:** `HealthRecordType` (vaccination, treatment, inspection, deworming, vitamin, other)
- **Migration:** `create_hen_health_records_table` with hen_batch_id FK
- **Scopes:** `scopeUpcoming()` — next_due_date within 7 days; `scopeOverdue()` — next_due_date in the past
- **Relation Manager:** `HealthRecordsRelationManager` on HenBatchResource with type filter, overdue badge indicator

## Files Changed
- `app/Models/HenHealthRecord.php`
- `app/Enums/HealthRecordType.php`
- `database/migrations/xxxx_create_hen_health_records_table.php`
- `app/Filament/Resources/HenBatchResource/RelationManagers/HealthRecordsRelationManager.php`
- `app/Filament/Resources/HenBatchResource.php` (registered relation manager)

## Verification
- Navigate to a Hen Batch detail and add health records of different types
- Verify the relation manager shows records with correct type badges
- Create records with past next_due_date and confirm overdue scope works
- Create records with near-future next_due_date and confirm upcoming scope works
- Run `composer test` and `composer analyse` with no errors
