# Feature #62 — Add Quarantine to HenBatchStatus

**Priority:** MEDIUM
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add a `Quarantine` case to the HenBatchStatus enum, aligning with the PoC's `cuarentena` status. Quarantine is used when a batch is isolated due to health concerns and cannot produce or be moved.

## Scope
- Add `Quarantine` case to existing `HenBatchStatus` enum
- No migration needed (enum values are stored as strings)

## Implementation Details
- **Enum:** Add `Quarantine` case to `HenBatchStatus` with value `'quarantine'`, label `'Cuarentena'`, color `'danger'`, icon `'heroicon-o-shield-exclamation'`
- No model or migration changes required — the enum is cast from a string column

## Files Changed
- `app/Enums/HenBatchStatus.php`

## Verification
- Verify the Quarantine option appears in HenBatch status dropdowns
- Change a batch to Quarantine status and confirm it saves/displays correctly
- Verify the danger color and shield icon render in the table
- Run `composer test` and `composer analyse` with no errors
