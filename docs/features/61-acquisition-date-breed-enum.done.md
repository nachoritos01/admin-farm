# Feature #61 — Acquisition Date + Breed Enum

**Priority:** HIGH
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Replace the free-text breed field on hen batches with a fixed Breed enum (matching the PoC's 5 breeds) and add an acquisition_date field to enable automatic age calculation instead of manual age_weeks entry.

## Scope
- New `Breed` enum with 5 standard poultry breeds
- Add `acquisition_date` column to `hen_batches`
- Auto-calculate `age_weeks` from acquisition_date
- Update HenBatchResource form to use enum select and date picker

## Implementation Details
- **Enum:** `Breed` — RhodeIslandRed, Leghorn, PlymouthRock, Araucana, Other (with Spanish labels: "Rhode Island Red", "Leghorn", "Plymouth Rock", "Araucana", "Otra")
- **Migration:** Add `acquisition_date` (date, nullable) to `hen_batches`
- **Model:** Cast `breed` to `Breed` enum, add `getAgeWeeksAttribute()` accessor that computes weeks from `acquisition_date` (falls back to stored `age_weeks` if no date)
- **Resource:** Change breed TextInput → Select with `Breed::class` options, add DatePicker for `acquisition_date`, make `age_weeks` read-only/disabled (auto-calculated)

## Files Changed
- `app/Enums/Breed.php`
- `database/migrations/xxxx_add_acquisition_date_to_hen_batches_table.php`
- `app/Models/HenBatch.php`
- `app/Filament/Resources/HenBatchResource.php`

## Verification
- Create a hen batch with acquisition_date and verify age_weeks auto-calculates
- Verify breed dropdown shows all 5 enum options with Spanish labels
- Create a batch without acquisition_date and confirm age_weeks still works manually
- Run `composer test` and `composer analyse` with no errors
