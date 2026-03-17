# Convert Proof-of-Concept App to Multi-Tenant SaaS

Planning workflow to convert a proof-of-concept application (Next.js, React, Angular, Vue, or any framework) into a production-ready multi-tenant Laravel SaaS using this template as the base.

This is a **planning-first** approach: Claude Code reads your PoC context + screenshots, generates numbered feature docs in `docs/features/` (same pattern as `/audit all`), and then executes them one by one.

---

## How to Use

### 1. Create the project

```bash
# Create new repo from this GitHub template, then clone it
git clone git@github.com:your-org/your-new-saas.git
cd your-new-saas
```

### 2. Prepare the conversion inputs

Create these files in your new project:

```
conversion/
  context.md          # PoC audit: modules, fields, business rules (see format below)
  screenshots/        # Screenshots of every page/view from your PoC app
    dashboard.png
    orders-list.png
    orders-form.png
    settings.png
    ...
```

### 3. Run the planning phase

Open Claude Code in the project and run:

```
/plan Read conversion/context.md and all screenshots in conversion/screenshots/, then generate the conversion plan as numbered feature docs in docs/features/
```

Claude will:
1. Read `conversion/context.md` for the structured audit
2. Analyze each screenshot in `conversion/screenshots/` visually
3. Map PoC modules against existing template capabilities (using the rules below)
4. Generate `docs/conversion-mapping.md` with the full module mapping
5. Generate numbered `docs/features/XX-*.md` files — one per feature to implement
6. Present the full plan for your approval before any code is written

### 4. Execute feature by feature

After approving the plan, implement each feature doc:

```
Implement docs/features/50-add-egg-production-module.md
```

Commit after each feature. Tag + release after merging to develop.

---

## `conversion/context.md` Format

Write this file following this structure. The more detail you provide, the better the plan.

```markdown
# PoC Conversion Context

## Application Name
[Name of the proof-of-concept app]

## Tech Stack
[e.g., Next.js 14, React, Tailwind, Supabase, Firebase, etc.]

## Target Domain
[e.g., Egg farm management, Veterinary clinic, Restaurant, Gym, etc.]

## Implemented Modules

| Module | Route | Functionality | Fields / Data |
|--------|-------|---------------|---------------|
| Dashboard | / | KPIs, charts, alerts | revenue, orders, stock level |
| Production | /production | CRUD, stats, CSV export | date, quantity, batch_id, quality |
| Inventory | /inventory | Stock control, alerts | item, quantity, min_stock, category |
| ... | ... | ... | ... |

## Business Rules
- [e.g., "Orders can only be cancelled within 24 hours"]
- [e.g., "Stock is decremented automatically when an order is completed"]
- [e.g., "Prices vary by customer type: retail vs wholesale"]

## Proposed Improvements (from PoC roadmap)

| # | Feature | Priority | Description |
|---|---------|----------|-------------|
| 1 | Advanced Reports | High | Profitability by client, margin analysis |
| 2 | Health Records | Medium | Vaccination tracking, medical history |
| ... | ... | ... | ... |

## External Integrations
- [e.g., WhatsApp API for notifications]
- [e.g., Google Maps for route optimization]

## User Roles (in the PoC)
- [e.g., Admin — full access]
- [e.g., Driver — only sees assigned deliveries]

## Screenshots
See conversion/screenshots/ folder. Each file is named after the view it represents.
```

---

## Planning Output

### `docs/conversion-mapping.md`

Claude generates this mapping document first:

```markdown
# Conversion Mapping: [App Name]

## Module Mapping

| PoC Module | Action | Template Equivalent | New Model? | Feature Doc |
|------------|--------|---------------------|------------|-------------|
| Dashboard | EXTEND | Filament Dashboard widgets | No | #50 |
| Orders | SKIP | OrderResource exists | No | — |
| Customers | SKIP | CustomerResource exists | No | — |
| Production | NEW | — | ProductionRecord | #51 |
| Inventory | EXTEND | Item model + new fields | No | #52 |
| Suppliers | NEW | — | Supplier | #53 |
| Expenses | NEW | — | Expense, ExpenseCategory | #54 |

## Existing Models to Extend

| Model | New Columns | New Relationships |
|-------|-------------|-------------------|
| Item | stock, min_stock, unit | hasMany(ProductionRecord) |
| Customer | customer_type, zone | — |

## New Roles/Permissions

| PoC Role | Maps to Template Role | Notes |
|----------|----------------------|-------|
| Admin | owner | Full access |
| Driver | Custom (new) | View assigned deliveries only |

## Plan Limit Adjustments (config/saas.php)

| Feature | Starter | Growth | Pro |
|---------|---------|--------|-----|
| batches | 5 | 20 | unlimited |
| drivers | 1 | 5 | unlimited |
```

### Feature Docs (`docs/features/XX-*.md`)

One per feature, numbered after the last existing doc:

```markdown
# [Feature Title]

**Status:** Pending
**Priority:** [High/Medium/Low]
**Depends on:** [other doc numbers, or "None"]
**Branch:** feature/[name]

## Overview
[What this feature does and why]

## Models
### [ModelName] (new)
- tenant_id (FK), [fields with types]
- Relationships, Scopes, Traits: HasFactory, BelongsToTenant

## Migration
[Key columns, indexes, constraints]

## Filament Resource
- Form: [fields]
- Table: [columns, filters, actions]
- Navigation: [group, icon]
- Module flag: [plugin name or "core"]

## Seeder
[Sample data — must be idempotent]

## Tests
- [ ] CRUD operations
- [ ] Tenant isolation
- [ ] Plan limits / module flags (if applicable)

## Screenshots Reference
[Which files from conversion/screenshots/ map to this feature]
```

After implementation, rename to `.done.md`.

---

## Prompt (Conversion Rules + Execution Plan)

```
You are converting a proof-of-concept application into a production multi-tenant SaaS using this Laravel template as the base. This template already includes: multi-tenancy, RBAC, Stripe billing, onboarding, plugin marketplace, customer portal, referral system, loyalty program, super-admin panel, and API (Sanctum).

Read CLAUDE.md and .claude/context/architecture.md before starting.
Read conversion/context.md for the PoC audit.
Read all images in conversion/screenshots/ to visually understand the PoC UI.

## Conversion Rules

### What to KEEP from the template (do NOT recreate)
- Multi-tenancy system (Tenant model, BelongsToTenant trait, global scopes)
- Authentication (User, Customer guards, Filament auth, Sanctum API)
- RBAC (Spatie roles: owner, admin, ventas, produccion)
- Billing (Stripe Cashier, plans: starter/growth/pro, plan limits in config/saas.php)
- Onboarding flow and trial system
- Plugin marketplace and module flags (hasModule)
- Super-admin panel (/super-admin with tenant management, impersonation)
- Customer portal (/my-account with customer auth)
- Referral and loyalty systems
- SaaS landing page, registration, security headers
- CI/CD, Docker, Railway deployment
- Existing generic models: Order, OrderLine, Customer, CustomerAddress, Item, Location, Quote, Invoice, Payment

### What to CONVERT from the PoC
- Domain-specific modules that don't exist in the template → new Filament resources
- Domain-specific models → new Eloquent models with BelongsToTenant trait
- Dashboard widgets/KPIs → new Filament widgets (replacing or extending existing ones)
- Reports → new Filament pages or extend existing report system
- Configuration/settings → extend tenant settings (HasEncryptedSettings) or config files
- Calendar/scheduling → new Livewire components or Filament pages

### What to SKIP (already handled by the template)
- User management → already exists (Team Management in Filament)
- Client/customer CRUD → already exists (CustomerResource + CustomerAddress)
- Order/sales CRUD → already exists (OrderResource + OrderLine + Item)
- Payment tracking → already exists (Payment model + methods)
- Invoice generation → already exists (Invoice model + PDF)
- Location management → already exists (LocationResource)
- Basic settings/config → already exists (BusinessConfig + tenant settings)

## Execution Plan

Work in two stages: **Planning** (Phase 0) then **Implementation** (Phases 1-9).
Phase 0 uses `/plan` mode. Phases 1-9 execute one feature doc at a time.

### Phase 0: Planning — Analysis, Mapping & Feature Docs
1. Read `conversion/context.md` for the PoC audit
2. Read all images in `conversion/screenshots/` to understand the PoC UI visually
3. Read `.claude/context/architecture.md` to understand existing template models
4. Create `docs/conversion-mapping.md` with:
   - Table: PoC Module → Template Equivalent (or "NEW")
   - For each NEW module: proposed model name, relationships, Filament resource
   - For each EXISTING module to extend: what fields/features to add
   - New roles/permissions mapping
   - Plan limit adjustments for config/saas.php
5. Generate numbered `docs/features/XX-*.md` — one per feature to implement
   - Number sequentially after the last existing feature doc
   - Each doc follows the feature doc format (Status: Pending, models, migration, resource, seeder, tests)
   - Reference which screenshots from `conversion/screenshots/` map to each feature
   - Respect dependency order (e.g., models before resources that use them)
6. Present the full plan (mapping + feature docs list) for user approval
7. **DO NOT write any code until the user approves the plan**

### Phase 1: Database — New Models & Migrations
For each NEW module identified in Phase 0:
1. Create migration with tenant_id FK, proper indexes, and constraints
2. Create Eloquent model with:
   - `use HasFactory, BelongsToTenant;`
   - Typed `$fillable`, `$casts`
   - Relationships to Tenant and other models
   - Scopes for common queries
3. For EXISTING models that need extension:
   - Create migration to add new columns (never modify existing migrations)
   - Update model: add new fillable fields, casts, relationships

### Phase 2: Database — Extend Existing Models
For PoC features that map to existing template models:
1. Add missing columns via new migrations (e.g., Item needs `category`, `stock`)
2. Update $fillable and $casts on existing models
3. Add new relationships if needed
4. Keep backward compatibility — don't remove existing fields

### Phase 3: Filament Admin Resources
For each NEW model:
1. Create Filament Resource with:
   - Form schema matching the model fields
   - Table with searchable, sortable columns
   - Proper filters (Select, Ternary, Date)
   - Actions (view, edit, delete + domain-specific)
2. Register in AdminPanelProvider navigation
3. Add `shouldRegisterNavigation()` with `hasModule()` check if it should be a plugin

For EXISTING resources that need extension:
1. Add new form fields and table columns
2. Add new filters or actions
3. Don't rewrite — extend

### Phase 4: Dashboard & Widgets
1. Map PoC dashboard KPIs to Filament widgets
2. Extend existing StatsOverview widget or create new ones
3. Add chart widgets for domain-specific analytics
4. All widgets must scope by `Filament::getTenant()` or session tenant_id

### Phase 5: Seeders & Sample Data
1. Create seeders for new models (MUST be idempotent — use firstOrCreate/updateOrCreate)
2. Extend TenantSeedService if new models need seed data per tenant
3. Add new seeder to DatabaseSeeder call chain
4. Test: `php artisan migrate:fresh --seed` must work cleanly

### Phase 6: Plan Limits & Module Flags
1. Update `config/saas.php` plan limits if new modules have usage caps
2. Add module flags in Plugin seeder if new features should be toggleable
3. Wire `hasModule()` checks into navigation and routes

### Phase 7: API Endpoints (if needed)
1. Add routes in `routes/api.php` under v1 group with Sanctum auth
2. Create API controllers following existing pattern (V1 namespace)
3. Scope all queries by tenant (from Sanctum token → user → tenant)

### Phase 8: Tests
1. Create Feature tests for each new Filament resource (CRUD + tenant isolation)
2. Create Unit tests for model logic and scopes
3. Test plan limits and module flag gating
4. Run: `composer test` — all must pass
5. Run: `composer analyse` — PHPStan level 5 clean

### Phase 9: Documentation & Cleanup
After each feature doc is implemented:
1. Update the feature doc status from "Pending" to "Done"
2. Rename the feature doc to `.done.md` (e.g., `50-add-production-module.md` → `50-add-production-module.done.md`)
3. Update `docs/conversion-mapping.md` status column
4. Update `.claude/context/current.md` with new state
5. Update `.claude/context/architecture.md` with new models/relationships
6. Run `composer format` for code style

## Important Reminders

### Planning Phase
- Read ALL screenshots in conversion/screenshots/ before generating the plan
- Generate docs/conversion-mapping.md FIRST, then feature docs
- Number feature docs sequentially after the last existing doc in docs/features/
- Each feature doc must be self-contained (a developer can implement it independently)
- DO NOT write any code until the user approves the plan
- Mark dependencies between feature docs explicitly

### Implementation Phase
- ALWAYS use `config()` in models/controllers, NEVER `env()` directly
- Seeders MUST be idempotent (firstOrCreate or updateOrCreate)
- Password cast 'hashed' on User: do NOT bcrypt() when creating via model
- All new models with tenant data MUST use the BelongsToTenant trait
- Commit after each feature with conventional commits (feat/fix/docs)
- Verify UI with MCP Chrome DevTools after features that add views
- English for code, commits, and documentation
- After implementing a feature doc, rename it to .done.md
- Update docs/conversion-mapping.md as features are completed
```

---

## Example: `conversion/context.md`

```markdown
# PoC Conversion Context

## Application Name
Granja La Guadalupana

## Tech Stack
Next.js 14, React, Tailwind CSS, Supabase (PostgreSQL)

## Target Domain
Egg farm management — production tracking, sales, deliveries

## Implemented Modules

| Module | Route | Functionality | Fields / Data |
|--------|-------|---------------|---------------|
| Dashboard | / | KPIs, charts, alerts, quick actions | revenue, orders_count, production_today, stock_level |
| Production | /production | Daily egg production CRUD, stats, CSV export | date, batch_id, quantity, broken, quality_grade |
| Hens | /hens | Batch management, movements, laying rate | batch_name, breed, count, age_weeks, laying_rate |
| Clients | /clients | CRUD, client types, delivery zones | name, phone, type(retail/wholesale), zone, balance |
| Orders | /orders | CRUD, statuses, multiple line items | customer_id, items[], status, delivery_date, total |
| Shipments | /shipments | Scheduling, driver assignment, routes | order_ids[], driver_id, date, zone, status |
| Suppliers | /suppliers | Basic CRUD for feed/medicine suppliers | name, phone, category(feed/medicine/equipment) |
| Expenses | /expenses | CRUD, categories, monthly charts | date, category, amount, supplier_id, notes |
| Calendar | /calendar | Activity view (deliveries, vaccinations) | — |
| Settings | /settings | Goals, notifications, business data | business_name, tax_id, daily_goal, alerts |

## Business Rules
- Production is recorded daily, one record per batch per day
- Stock = sum(production) - sum(sold) - sum(broken)
- Wholesale customers get 10% discount automatically
- Orders transition: pending → confirmed → shipped → delivered → completed
- Drivers can only see their own assigned shipments
- Expenses are categorized: feed, medicine, equipment, utilities, labor, other
- Low stock alert triggers when stock < 3 days of average daily sales
- Laying rate = (eggs_produced / hen_count) * 100, tracked per batch

## Proposed Improvements

| # | Feature | Priority | Description |
|---|---------|----------|-------------|
| 1 | Advanced Reports | High | Profitability by client, production vs sales, margin analysis |
| 2 | Egg Inventory | High | Real-time stock, size/quality classification, alerts |
| 3 | Price History | Medium | Track price changes, volume discounts, custom price lists |
| 4 | Hen Health | Medium | Vaccination schedule, medical history, medication tracking |
| 5 | Route Optimization | Low | Group deliveries by zone, suggest optimal route |
| 6 | Accounts Receivable | High | Pending balances per client, payment history |
| 7 | WhatsApp Notifications | Low | Order confirmation, delivery reminders |

## External Integrations
- WhatsApp Business API (notifications) — future
- Google Maps (delivery routes) — future

## User Roles (in the PoC)
- Admin — full access to everything
- Driver — view assigned shipments, update delivery status
- Production — register daily production, view batches

## Screenshots
See conversion/screenshots/ folder:
- dashboard.png, production-list.png, production-form.png
- hens-list.png, hens-detail.png, clients-list.png
- orders-list.png, orders-form.png, shipments.png
- expenses-list.png, expenses-charts.png, calendar.png, settings.png
```

---

## Tips

1. **Be thorough in `context.md`** — include field names, data types, and business rules. The more detail, the better the generated feature docs.
2. **Screenshot everything** — Claude analyzes screenshots visually to understand UI patterns, form layouts, and data relationships that text descriptions miss.
3. **Mark priorities** — helps Claude order the feature docs correctly (High first).
4. **Note business rules explicitly** — things like "stock decrements on order completion" are critical for correct implementation.
5. **List integrations** — even future ones, so the plan accounts for them as plugin modules.
6. **Review the mapping** — `docs/conversion-mapping.md` is your chance to adjust before any code is written. Take your time here.
