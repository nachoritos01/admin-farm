# Conversion Plan — Granja La Guadalupana

**Created:** 2026-03-17
**Status:** IN PROGRESS
**Branch:** `feature/farm-conversion` (from develop)

---

## Overview

Converting the generic multi-tenant SaaS template into a production egg farm management system. The template already provides multi-tenancy, RBAC, Stripe billing, customer portal, plugin marketplace, loyalty program, and generic CRUD. This conversion adds domain-specific models and extends existing ones.

---

## 1. Module Mapping

| PoC Module | Action | Template Equivalent |
|---|---|---|
| Dashboard | **EXTEND** | StatsOverview + chart widgets |
| Produccion | **NEW** | — |
| Gallinas | **NEW** | — |
| Clientes | **EXTEND** | CustomerResource |
| Pedidos | **SKIP** | OrderResource + OrderLine |
| Envios | **NEW** | — |
| Proveedores | **NEW** | — |
| Gastos | **NEW** | — |
| Calendario | **DEFER** | Future |
| Configuracion | **SKIP** | TenantSettings + BusinessConfig |

### Proposed Improvements

| # | Improvement | Action |
|---|---|---|
| 1 | Advanced Reports | NEW page |
| 2 | Egg Inventory | NEW service + page |
| 3 | Price History | NEW model |
| 4 | Hen Health | NEW model + relation manager |
| 5 | Route Optimization | DEFER |
| 6 | Accounts Receivable | NEW page |
| 7 | WhatsApp | OUT OF SCOPE |
| 8 | PWA/Offline | OUT OF SCOPE |

---

## 2. Existing Models to Extend

| Model | New Columns | Migration |
|---|---|---|
| **Customer** | `customer_type` (enum: retail/wholesale/store), `zone` (string) | `add_farm_fields_to_customers_table` |
| **Item** | `unit` (string), `egg_size` (enum), `egg_quality` (enum), `wholesale_price` (decimal:2) | `add_egg_fields_to_items_table` |
| **Order** | `shipment_id` (FK nullable), `delivery_date` (date nullable) | `add_shipment_fields_to_orders_table` |
| **Tenant** | — (no schema change) | Add `hasMany` for all new models + update `usageCounts()`/`planLimits()` |

---

## 3. New Models (8)

| Model | Key Fields | Relationships |
|---|---|---|
| **HenBatch** | name, breed, initial_count, current_count, age_weeks, status (enum), is_active | hasMany(ProductionRecord, HenMovement, HenHealthRecord) |
| **HenMovement** | hen_batch_id, type (enum: addition/removal/death/transfer/sale), quantity, date, reason | belongsTo(HenBatch) — auto-recalculates current_count |
| **ProductionRecord** | hen_batch_id, date, qty_morning, qty_afternoon, qty_total, broken, net_production, quality_grade | belongsTo(HenBatch) — unique [tenant_id, hen_batch_id, date] |
| **Supplier** | name, contact_name, phone, email, category (enum: feed/medicine/equipment/packaging/other), is_active | hasMany(Expense) |
| **Expense** | supplier_id, category (enum), amount, description, date, payment_method (reuse PaymentMethod) | belongsTo(Supplier) |
| **Shipment** | driver_id (FK users), status (enum: scheduled/in_transit/delivered/cancelled), scheduled_date, zone | hasMany(Order), belongsTo(User) |
| **PriceHistory** | item_id, price, previous_price, effective_date, changed_by, reason | belongsTo(Item, User) |
| **HenHealthRecord** | hen_batch_id, type (enum: vaccination/treatment/observation/mortality), date, medication, dosage, next_due_date | belongsTo(HenBatch) |

All models use `BelongsToTenant` trait + `HasFactory`.

---

## 4. New Enums (9)

`CustomerType`, `HenBatchStatus`, `HenMovementType`, `QualityGrade`, `EggSize`, `SupplierCategory`, `ExpenseCategory`, `ShipmentStatus`, `HealthRecordType`

---

## 5. Roles & Permissions

**New role:** `chofer` — `orders.view`, `shipments.view`, `shipments.update_status`

**New permissions (15):**
- `production.create`, `production.edit` → owner, admin, produccion
- `hen_batches.view`, `hen_batches.manage` → owner, admin (view also produccion)
- `suppliers.view`, `suppliers.manage` → owner, admin (view also contabilidad)
- `expenses.view`, `expenses.create`, `expenses.manage` → owner, admin (view/create also contabilidad)
- `shipments.view`, `shipments.manage`, `shipments.update_status` → owner, admin, ventas (view/update also chofer)
- `inventory.view` → owner, admin, ventas, produccion
- `health_records.view`, `health_records.manage` → owner, admin (view also produccion)

---

## 6. Plan Limits (additions to config/saas.php)

| Limit | Starter | Growth | Pro |
|---|---|---|---|
| max_hen_batches | 5 | 20 | unlimited |
| max_suppliers | 10 | 50 | unlimited |
| max_shipments (monthly) | 50 | 200 | unlimited |

---

## 7. New Plugins (PluginSeeder)

| Slug | Name | Free? | Included in |
|---|---|---|---|
| production | Egg Production | Yes | starter, growth, pro |
| shipments | Shipments & Deliveries | Yes | starter, growth, pro |
| suppliers | Suppliers | Yes | starter, growth, pro |
| expenses | Expense Tracking | Yes | starter, growth, pro |
| inventory | Inventory Control | No ($4.99/mo) | growth, pro |
| hen_health | Hen Health Records | No ($4.99/mo) | growth, pro |

---

## 8. Implementation Phases & Feature Docs

### Phase 1 — Foundation (no dependencies)

| # | Title | Status |
|---|---|---|
| 46 | Hen Batch + Movement models + resource | Pending |
| 48 | Extend Customer (customer_type, zone) | Pending |
| 49 | Supplier model + resource | Pending |
| 54 | Price History model + relation manager | Pending |

### Phase 2 — Core domain (depends on Phase 1)

| # | Title | Depends | Status |
|---|---|---|---|
| 47 | Production Record model + resource | #46 | Pending |
| 50 | Shipment model + resource | #48 | Pending |
| 51 | Expense model + resource | #49 | Pending |
| 56 | Hen Health Records | #46 | Pending |
| 59 | Extend Item for Egg Products | #48 | Pending |

### Phase 3 — Aggregation & UX

| # | Title | Depends | Status |
|---|---|---|---|
| 52 | Farm Dashboard widgets | #46,47,48,51 | Pending |
| 53 | Egg Inventory service + page | #47 | Pending |
| 57 | Accounts Receivable page | #48 | Pending |
| 58 | Roles, Permissions, Seeders | all new models | Pending |

### Phase 4 — Reports

| # | Title | Depends | Status |
|---|---|---|---|
| 55 | Advanced Reports page | #47,48,51,53 | Pending |

---

## 9. Key Architectural Decisions

1. **Inventory as a Service, not a Model** — Stock derived from `sum(production) - sum(sold) - sum(broken)`. `InventoryService` calculates on demand with caching.
2. **Shipment groups Orders** — One shipment = one delivery run by a driver with multiple orders for the same zone.
3. **Wholesale pricing via `wholesale_price` field** — Falls back to percentage discount when null.
4. **Farm plugins included in all plans by default** — Core farm features are free plugins. Advanced features (inventory, hen_health) are paid in starter.
5. **Reuse existing enums/services** — `PaymentMethod` reused for expenses, `OrderStatus` maps to PoC order flow.

---

## 10. Deferred / Out of Scope

| Item | Reason | When |
|---|---|---|
| Calendar module | UI convenience — data accessible via resources | Post-launch plugin |
| WhatsApp Integration | External API, low priority | Future paid plugin |
| PWA/Offline | Major infra change | Future phase |
| Route Optimization | Requires Google Maps API | Future paid plugin |

---

## 11. Verification Checklist

After all features are implemented:
- [ ] `DB_PORT=5433 php artisan migrate:fresh --seed` — clean run
- [ ] `DB_PORT=5433 composer test` — all tests pass
- [ ] `composer analyse` — PHPStan level 5 clean
- [ ] MCP Chrome DevTools visual verification of each new resource/page
- [ ] Each feature doc renamed to `.done.md` after implementation

---
*Last updated: 2026-03-17*
