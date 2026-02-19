# Pharmacy Inventory Management System (CodeIgniter 4)

Backend-first scaffold for a local XAMPP deployment using:

- CodeIgniter 4 (MVC + native PHP views)
- Custom Repository + Service layers (lean controllers)
- MySQL (XAMPP)
- Vite dev server with HMR
- PHPUnit unit + integration testing

## Core Workflow Coverage

Implemented database and service primitives for:

1. Purchase Request
2. Approval
3. Purchase Order
4. PO Request
5. Convert Receiving
6. Enter Quantity Inventory
7. Issuance

## Local Setup (XAMPP + Vite)

1. Enable Apache and MySQL in XAMPP.
2. Ensure PHP `intl` extension is enabled in `C:\xampp\php\php.ini`.
3. Create `.env` from `env`:

	```bash
	copy env .env
	```

4. Update `.env` values:

	```ini
	CI_ENVIRONMENT = development
	app.baseURL = 'http://localhost:8080/'
	database.default.hostname = localhost
	database.default.database = pharmacy_inventory
	database.default.username = root
	database.default.password =
	database.default.DBDriver = MySQLi
	database.default.port = 3306
	```

5. Install PHP dependencies:

	```bash
	composer install
	```

6. Install frontend dependencies:

	```bash
	npm install
	```

7. Run migrations and seed initial roles/admin:

	```bash
	php spark migrate
	php spark db:seed InitialDataSeeder
	```

8. Start servers:

	```bash
	php spark serve --host 0.0.0.0 --port 8080
	npm run dev
	```

9. Open app at `http://localhost:8080`.

Default seeded admin:

- Email: `admin@pharmacy.local`
- Password: `Admin@123`

## Layers and Responsibilities

- Controllers: request/response only (`AuthController`, `DashboardController`)
- Services: business orchestration (`AuthService`, `PurchaseWorkflowService`)
- Repositories: persistence abstraction (`app/Repositories/*`)
- Models: table mapping + allowed fields (`app/Models/*`)

## Security Defaults

- CSRF filter enabled globally
- Output escaping via `esc()` in views
- Role-based route protection via custom filters
- Password hashing via `password_hash()` / `password_verify()`

## Tests

Run all tests:

```bash
composer test
```

Added:

- Unit tests: `tests/unit/Services/AuthServiceTest.php`
- Unit tests: `tests/unit/Services/PurchaseWorkflowServiceTest.php`
- Integration tests: `tests/integration/Controllers/AuthRoutesTest.php`

## Workflow API (Backend Skeleton)

Authenticated role scope: `Admin`, `Employee`.

- `POST /api/workflow/purchase-requests`
- `POST /api/workflow/purchase-requests/{id}/approve`
- `POST /api/workflow/purchase-orders`
- `POST /api/workflow/po-requests`
- `POST /api/workflow/receivings/convert`
- `POST /api/workflow/issuances`

## Optimization Plan (Early)

- Indexed high-frequency columns (`status`, FK columns, workflow reference pairs)
- Transactional repository writes for multi-table operations
- Inventory movement ledger table for auditable stock changes
- Service-layer boundaries prepared for future caching/queueing without controller rewrites

## Detailed Database Schema

See `docs/database_schema.md`.