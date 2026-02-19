# How to Run the Pharmacy Inventory System (Windows + XAMPP)

Follow these steps in order.

## 1) Prerequisites

- XAMPP installed (Apache + MySQL)
- PHP available in your terminal (or use XAMPP PHP path)
- Composer installed
- Node.js + npm installed

## 2) Start XAMPP Services

1. Open XAMPP Control Panel.
2. Start **Apache**.
3. Start **MySQL**.

## 3) Configure Environment File

From project root, create `.env` from `env`:

```powershell
copy env .env
```

Open `.env` and make sure these values are set:

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

## 4) Enable Required PHP Extension (`intl`)

CodeIgniter 4 tests and some framework internals need `intl`.

1. Open `C:\xampp\php\php.ini`.
2. Find `;extension=intl` and remove the `;` so it becomes:

```ini
extension=intl
```

3. Restart Apache (and restart terminal if needed).
4. Verify:

```powershell
php -m | findstr intl
```

## 5) Install Dependencies

Install PHP packages:

```powershell
composer install
```

Install frontend packages:

```powershell
npm install
```

## 6) Create Database and Run Migrations

1. Create database `pharmacy_inventory` in phpMyAdmin (or MySQL CLI).
2. Run migrations:

```powershell
php spark migrate
```

3. Seed default roles/admin:

```powershell
php spark db:seed InitialDataSeeder
```

## 7) Run the Application

Use two terminals in project root.

Terminal A (CodeIgniter server):

```powershell
php spark serve --host 0.0.0.0 --port 8080
```

Terminal B (Vite dev server):

```powershell
npm run dev
```

Open browser:

- http://localhost:8080

## 8) Default Login (Seeded Admin)

- Email: `admin@pharmacy.local`
- Password: `Admin@123`

## 9) Optional: Run Tests

```powershell
composer test
```

If tests fail with `Locale` or `intl` errors, re-check Step 4.

## 10) Useful Routes

- Login: `/login`
- Dashboard: `/dashboard`
- Workflow: `/workflow`
- Purchase Requests CRUD: `/purchase-requests`
- Purchase Orders CRUD: `/purchase-orders`
