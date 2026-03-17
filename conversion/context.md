# PoC Conversion Context

## Application Name
Granja La Guadalupana — Panel Administrativo

## Tech Stack
[Pending — confirm original PoC stack: Next.js/React/Vue/etc.]

## Target Domain
Egg farm management (granja de huevos) — production tracking, sales, deliveries, expenses

## Implemented Modules

| Module | Route | Functionality | Fields / Data |
|--------|-------|---------------|---------------|
| Dashboard | / | KPIs, charts, alerts, quick actions | revenue, orders, stock level, production |
| Producción | /produccion | CRUD, statistics, CSV export | date, quantity, lot, quality, notes |
| Gallinas | /gallinas | Lots, movements, laying rate | lot_name, quantity, laying_rate, age, status |
| Clientes | /clientes | CRUD, customer types, zones | name, type (retail/wholesale), zone, contact |
| Pedidos | /pedidos | CRUD, statuses, multiple items | customer, items, status, total, date |
| Envíos | /envios | Scheduling, drivers, routes | order, driver, date, route, status |
| Proveedores | /proveedores | Basic CRUD | name, contact, product_type, notes |
| Gastos | /gastos | CRUD, categories, charts | amount, category, date, description |
| Calendario | /calendario | Activity view | events, dates, type |
| Configuración | /configuracion | Goals, notifications, data | goals, notification_prefs, farm_data |

## Business Rules
- Orders track multiple line items (different egg types/quantities)
- Production is tracked per hen lot with laying rate calculations
- Customers are categorized by type (retail vs wholesale) and delivery zone
- Shipments are assigned to drivers with route planning
- Expenses are categorized for financial tracking
- Dashboard KPIs aggregate production, sales, and expense data

## Proposed Improvements (from PoC roadmap)

| # | Feature | Priority | Description |
|---|---------|----------|-------------|
| 1 | Reportes y Análisis Avanzados | High | Profitability by customer, margin analysis (income - expenses), production by lot, monthly/annual comparisons, trend projections |
| 2 | Inventario de Huevos | High | Current stock control, low-inventory alerts, production vs sales reconciliation, size/quality classification |
| 3 | Historial de Precios | Medium | Price change log, differentiated pricing by customer/volume, custom price lists |
| 4 | Salud de Gallinas | Medium | Vaccination records, medical history per lot, pending vaccine alerts, medication tracking |
| 5 | Ruta de Entregas Optimizada | Medium | Zone-based delivery grouping, customer map, optimal route calculation |
| 6 | Cuentas por Cobrar | Medium | Outstanding balance per customer, payment history, collection reminders, account statements |
| 7 | Integración WhatsApp | Low | Order confirmation, delivery reminders, payment notifications |
| 8 | Modo Offline/PWA | Low | Offline functionality, background sync |

## Suggested New Sections

| Section | Route | Description |
|---------|-------|-------------|
| Inventario | /inventario | Egg stock control and availability |
| Reportes | /reportes | Reports and analytics center |
| Salud | /salud | Medical history and vaccinations |
| Cuentas | /cuentas | Accounts receivable and payments |
| Usuarios | /usuarios | Multi-user management (if needed) |

## External Integrations
- WhatsApp API for notifications (order confirmations, delivery reminders, payment alerts)
- Google Maps for delivery route optimization (future)

## User Roles (in the PoC)
- Admin — full access to all modules
- Driver — only sees assigned deliveries (implied from shipments module)

## Screenshots
See conversion/screenshots/ folder. Each file is named after the view it represents.
