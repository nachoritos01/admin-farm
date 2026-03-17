# Feature #63 — Death Cause Subtypes

**Priority:** LOW
**Status:** Pending
**Depends on:** None
**Phase:** 5 — Batch 1

## Summary
Add death cause sub-classification to hen movements, distinguishing between natural death, disease, accident, and unknown causes. The PoC separates `muerte_natural` from `enfermedad` while production currently has a single `Death` movement type.

## Scope
- New `DeathCause` enum with 4 causes
- Add `death_cause` column to `hen_movements`
- Conditionally show death_cause field when movement type is Death

## Implementation Details
- **Enum:** `DeathCause` — Natural, Disease, Accident, Unknown (with Spanish labels: "Muerte natural", "Enfermedad", "Accidente", "Desconocida")
- **Migration:** Add `death_cause` (string, nullable) to `hen_movements`
- **Model:** Cast `death_cause` to `DeathCause` enum, add to fillable
- **MovementsRelationManager:** Add `death_cause` Select field, visible only when `type === HenMovementType::Death` (using `->visible(fn (Get $get) => $get('type') === 'death')`)
- **Table:** Show `death_cause` column with badge, hidden by default (toggleable)

## Files Changed
- `app/Enums/DeathCause.php`
- `database/migrations/xxxx_add_death_cause_to_hen_movements_table.php`
- `app/Models/HenMovement.php`
- `app/Filament/Resources/HenBatchResource/RelationManagers/MovementsRelationManager.php`

## Verification
- Create a Death movement and verify death_cause select appears
- Create a non-Death movement and verify death_cause is hidden
- Verify the enum labels display in Spanish
- Run `composer test` and `composer analyse` with no errors
