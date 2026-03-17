# Current Development Context

**Last Updated:** 2026-03-17
**Branch:** feature/farm-phase1-foundation
**Latest Release:** v1.2.0
**Tests:** 351 tests, 893 assertions — ALL PASSING (0 failures)
**i18n:** es/en support — default es, per-user locale, language switcher
**PHPStan:** Level 5, 0 errors (baseline — larastan false positives)

## Project State

| Track | Status | Detail |
|-------|--------|--------|
| **Generic SaaS Template** | **COMPLETE** | All 22 phases done, v1.2.0 |
| **Farm Conversion (Guadalupana)** | **COMPLETE** | All 4 phases + Phase 5 PoC gap alignment |

## Farm Conversion — Granja La Guadalupana

**Plan:** [`docs/conversion-plan-guadalupana.md`](../../docs/conversion-plan-guadalupana.md)

### Implementation Summary

| Phase | Scope | Status |
|-------|-------|--------|
| **Phase 1 — Foundation** | #46 HenBatch, #48 Customer ext, #49 Supplier, #54 PriceHistory | **Done** |
| **Phase 2 — Core Domain** | #47 Production, #50 Shipment, #51 Expense, #56 HenHealth, #59 Item ext | **Done** |
| **Phase 3 — Aggregation** | #52 Dashboard, #53 Inventory, #57 Accounts Receivable, #58 Roles/Seeders | **Done** |
| **Phase 4 — Reports** | #55 Advanced Reports | **Done** |
| **Phase 5 — PoC Gap Alignment** | #60–#73 (14 features) | **Done** |

### Phase 5 — What Was Added

**New Models (1):** Purchase
**New Enums (7):** Breed, DeathCause, DeliveryType, UnitType, SupplierStatus, PurchaseUnit, SupplierStatus
**New Resources (1):** PurchaseResource (+ pages + relation manager)
**New Pages (1):** FarmCalendar
**New Service (1):** PricingService
**Modified Enums (4):** HenBatchStatus (+Quarantine), PaymentMethod (+CashOnDelivery), CustomerType (+Restaurant, NaturalStore), EggSize (+Mixed)
**Modified Models (8):** ProductionRecord (+dirty), HenBatch (+acquisition_date, age accessor), HenMovement (+death_cause), Customer (+preferred_price), Order (+delivery fields, shipping_cost), OrderLine (+egg_size, unit_type), Supplier (is_active→status, +address, products, rating), Shipment (+vehicle, route)
**Modified Pages (1):** TenantSettings (+farm config, egg price matrix)
**Modified Widget (1):** StatsOverview (+goal tracking)
**New Migrations (9):** dirty eggs, acquisition date, death cause, preferred price, delivery fields, egg fields on order lines, supplier extension, purchases table, vehicle/route on shipments
**New Plugin (1):** purchases

### What Was Added (Phases 1–4)

**New Models (8):** HenBatch, HenMovement, ProductionRecord, Supplier, Expense, Shipment, PriceHistory, HenHealthRecord
**New Enums (9):** CustomerType, HenBatchStatus, HenMovementType, QualityGrade, EggSize, SupplierCategory, ExpenseCategory, ShipmentStatus, HealthRecordType
**New Resources (4):** HenBatchResource, ProductionRecordResource, SupplierResource, ShipmentResource, ExpenseResource
**New Pages (3):** Inventory, AccountsReceivable, AdvancedReports
**New Service:** InventoryService
**Extended Models:** Customer (+type, zone), Item (+egg fields, wholesale_price), Order (+shipment_id, delivery_date), Tenant (+farm relationships)
**New Plugins (6):** production, shipments, suppliers, expenses, inventory, hen_health
**New Permissions (15):** hen_batches, suppliers, expenses, shipments, inventory, health_records
**New Role:** chofer

## Active Branches

| Branch | Purpose | Status |
|--------|---------|--------|
| `develop` | Integration | Stable — v1.2.0 |
| `feature/farm-phase1-foundation` | Farm conversion (5 phases) + i18n | Ready for PR |

---
*Update this file at the start and end of each development session.*
