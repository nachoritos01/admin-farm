# Feature #74 — i18n: Multi-Language Support (es/en)

**Priority:** HIGH
**Status:** Done
**Depends on:** None
**Phase:** Post-conversion

## Summary
Add full i18n infrastructure with Spanish (es) as default and English (en) support. Per-user locale preference stored in DB, language switcher in admin panel, all hardcoded strings translated via `__()`.

## Scope
- Locale config, migration, middleware
- Translation files (JSON + PHP arrays)
- Language switcher Livewire component
- Translate all 24 enums
- Translate all Filament resources, relation managers, pages
- Translate all Blade views and widgets

## Implementation Details

### Batch 1 — Infrastructure
- **Config:** `config/app.php` — set `locale => 'es'`, add `available_locales`
- **Migration:** Add `locale` column (string, default `'es'`) to `users` table
- **User Model:** Add `locale` to `$fillable`
- **Middleware:** `app/Http/Middleware/SetLocale.php` — sets app locale from authenticated user
- **Bootstrap:** Register `SetLocale` in web middleware group
- **Translation files:**
  - `lang/es.json`, `lang/en.json` — ~200 field labels/UI strings
  - `lang/{es,en}/enums.php` — all 24 enum translations
  - `lang/{es,en}/farm.php` — farm section strings
  - `lang/{es,en}/portal.php` — customer portal strings
- **Language Switcher:** `app/Livewire/LanguageSwitcher.php` + Blade view
- **Panel:** Register switcher via render hook in `AdminPanelProvider`

### Batch 2 — Translate All 24 Enums
Replace hardcoded `label()` return values with `__('enums.enum_name.case')` calls:
- OrderStatus, OrderPriority, HenBatchStatus, HenMovementType, CustomerType
- EggSize, QualityGrade, ExpenseCategory, SupplierCategory, SupplierStatus
- ShipmentStatus, HealthRecordType, PaymentMethod, DeliveryType, Breed
- DeathCause, UnitType, PurchaseUnit, PlanType, SubscriptionStatus
- BillingPeriod, BillingEventType, CancellationReason, LoyaltyTier

### Batch 3 — Translate Filament Resources, RelationManagers, Pages
- **Resources (11):** Replace static `$navigationLabel`/`$modelLabel` with method overrides using `__()`
- **RelationManagers (6):** Same pattern for `$title`/`$modelLabel`
- **Pages (13):** Replace `$title`/`$navigationLabel`/`$navigationGroup` with method overrides

### Batch 4 — Translate Blade Views and Widgets
- **Widget classes (5):** Replace `$heading` with `getHeading()`, wrap stat labels
- **Filament page views (5):** Wrap hardcoded text with `{{ __() }}`
- **Widget views (5):** Same pattern
- **Customer portal views (9):** Use `{{ __('portal.key') }}`
- **Other views (5):** PDF, SaaS landing, suspended page

## New Files (12)
| File | Purpose |
|------|---------|
| `database/migrations/..._add_locale_to_users_table.php` | locale column |
| `app/Http/Middleware/SetLocale.php` | Set app locale per-user |
| `app/Livewire/LanguageSwitcher.php` | Switcher component |
| `resources/views/livewire/language-switcher.blade.php` | Switcher UI |
| `lang/es.json` | Spanish labels (~200 entries) |
| `lang/en.json` | English labels (~200 entries) |
| `lang/es/enums.php` | Spanish enum translations |
| `lang/en/enums.php` | English enum translations |
| `lang/es/farm.php` | Spanish farm strings |
| `lang/en/farm.php` | English farm strings |
| `lang/es/portal.php` | Spanish portal strings |
| `lang/en/portal.php` | English portal strings |

## Modified Files (~84)
| Batch | Scope | Count |
|-------|-------|-------|
| 1 | config, User model, bootstrap, AdminPanelProvider | 4 |
| 2 | All 24 enums | 24 |
| 3 | 11 resources + 6 relation managers + 13 pages | 30 |
| 4 | ~5 widgets + ~24 Blade views | ~29 |

## Verification
```bash
DB_PORT=5433 php artisan migrate          # Add locale column
DB_PORT=5433 composer test                 # All pass
composer analyse                           # PHPStan level 5 clean
```
- Login → UI shows in Spanish by default
- Switch to English via language switcher → all labels change
- Switch back to Spanish → persists across reload
- Customer portal renders in default locale
- Filament core UI (pagination, table actions) follows locale
