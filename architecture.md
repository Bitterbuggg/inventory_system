# Architecture Overview

## 1) Stack

- Backend: CodeIgniter 4 (PHP 8.2+)
- Frontend assets: Vite with HMR
- Database: MySQL (XAMPP)
- Testing: PHPUnit (unit + integration)

## 2) Layered Design (Strict)

Request flow:

Client -> Route -> Controller -> Service -> Repository -> Model/DB

### Controller Layer

- Files: `app/Controllers/*`
- Responsibility: HTTP input/output only
- No business rules in controllers

### Service Layer

- Files: `app/Services/*`
- Responsibility: workflow orchestration and business decisions
- Includes inventory mutation and purchasing flow transitions

### Repository Layer

- Files: `app/Repositories/Contracts/*`, `app/Repositories/Database/*`
- Responsibility: persistence abstraction and transactions

### Model Layer

- Files: `app/Models/*`
- Responsibility: table mapping and allowed fields

## 3) Security Model

- CSRF globally enabled (`Config\Filters`)
- Output escaped with `esc()` in views
- Auth/session gate via `AuthFilter`
- Role-based gate via `RoleFilter` (Admin, Employee, IT Dev/Staff)
- Password hashing via native PHP password APIs

## 4) Operational Workflow Support

Business flow implemented in schema + service methods:

1. Purchase Request (`purchase_requests`, `purchase_request_items`)
2. Approval (`approvals`)
3. Purchase Order (`purchase_orders`, `purchase_order_items`)
4. PO Request (`po_requests`)
5. Convert Receiving (`receivings`, `receiving_items`)
6. Enter Quantity Inventory (`inventory_stocks`, `inventory_movements`)
7. Issuance (`issuances`, `issuance_items`)

## 5) Performance/Optimization Strategy (Initial)

- FK and status indexes included in initial migration
- Composite indexes for workflow queries (`workflow_type`, `reference_id`)
- Transactional inserts for multi-table operations
- Ledger-based inventory movements for auditability and future analytics

## 6) Project Structure (Current)

- `app/Controllers`: lean HTTP layer
- `app/Services`: business layer
- `app/Repositories`: contract + database implementations
- `app/Models`: CI models per table
- `app/Database/Migrations`: full pharmacy schema migration
- `app/Database/Seeds`: initial role and admin seed data
- `app/Views`: native PHP views (login/signup/dashboard)
- `resources`: Vite frontend entry assets
- `tests`: unit + integration tests

## 7) Last Updated

- 2026-02-19