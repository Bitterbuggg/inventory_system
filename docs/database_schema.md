# Detailed MySQL Schema - Pharmacy Inventory (Purchase Flow)

## Notes

- Engine: InnoDB
- Charset: `utf8mb4`
- Designed for transactional consistency and auditability
- Implemented by migration: `app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php`

## Master Tables

### `roles`
- `id` BIGINT PK
- `name` VARCHAR(40) UNIQUE (`Admin`, `Employee`, `IT Dev/Staff`)
- `description` VARCHAR(255) NULL
- `created_at`, `updated_at`

### `users`
- `id` BIGINT PK
- `role_id` BIGINT FK -> `roles.id`
- `full_name` VARCHAR(120)
- `email` VARCHAR(120) UNIQUE
- `password_hash` VARCHAR(255)
- `is_active` TINYINT(1)
- `last_login_at` DATETIME NULL
- `created_at`, `updated_at`, `deleted_at`
- Indexes: `role_id`, `is_active`

### `suppliers`
- `id` BIGINT PK
- `name` VARCHAR(120) UNIQUE
- `contact_person`, `phone`, `email`, `address`
- `is_active` TINYINT(1)
- `created_at`, `updated_at`

### `products`
- `id` BIGINT PK
- `sku` VARCHAR(60) UNIQUE
- `brand_name`, `generic_name`, `dosage_form`, `strength`, `unit`
- `description` TEXT NULL
- `reorder_level` INT UNSIGNED
- `created_at`, `updated_at`
- Indexes: `brand_name`, `generic_name`

## Purchase Request and Approval

### `purchase_requests`
- `id` BIGINT PK
- `request_no` VARCHAR(40) UNIQUE
- `requested_by` BIGINT FK -> `users.id`
- `status` VARCHAR(30) (`draft`, `submitted`, `approved`, `rejected`, `converted`)
- `requested_at` DATETIME
- `remarks` TEXT NULL
- `created_at`, `updated_at`
- Indexes: (`requested_by`, `status`)

### `purchase_request_items`
- `id` BIGINT PK
- `purchase_request_id` BIGINT FK -> `purchase_requests.id`
- `product_id` BIGINT FK -> `products.id`
- `requested_qty` INT UNSIGNED
- `approved_qty` INT UNSIGNED NULL
- `unit_cost_estimate` DECIMAL(12,2)
- `remarks` VARCHAR(255) NULL
- Indexes: (`purchase_request_id`, `product_id`)

### `approvals`
- `id` BIGINT PK
- `workflow_type` VARCHAR(40)
- `reference_id` BIGINT
- `approver_id` BIGINT FK -> `users.id`
- `status` VARCHAR(20) (`approved`, `rejected`, `pending`)
- `payload` JSON NULL
- `remarks` VARCHAR(255) NULL
- `acted_at` DATETIME NULL
- `created_at` DATETIME NULL
- Indexes: (`workflow_type`, `reference_id`), (`approver_id`, `status`)

## Purchase Order and PO Request

### `purchase_orders`
- `id` BIGINT PK
- `po_no` VARCHAR(40) UNIQUE
- `supplier_id` BIGINT FK -> `suppliers.id`
- `created_by` BIGINT FK -> `users.id`
- `approved_by` BIGINT FK -> `users.id` NULL
- `status` VARCHAR(30) (`draft`, `sent`, `partially_received`, `received`, `cancelled`)
- `order_date` DATE
- `expected_delivery_date` DATE NULL
- `total_amount` DECIMAL(14,2)
- `terms` VARCHAR(120) NULL
- `notes` TEXT NULL
- `created_at`, `updated_at`
- Indexes: (`supplier_id`, `status`), `created_by`

### `purchase_order_items`
- `id` BIGINT PK
- `purchase_order_id` BIGINT FK -> `purchase_orders.id`
- `product_id` BIGINT FK -> `products.id`
- `quantity` INT UNSIGNED
- `received_qty` INT UNSIGNED
- `unit_cost` DECIMAL(12,2)
- `line_total` DECIMAL(14,2)
- Indexes: (`purchase_order_id`, `product_id`)

### `po_requests`
- `id` BIGINT PK
- `request_no` VARCHAR(40) UNIQUE
- `purchase_order_id` BIGINT FK -> `purchase_orders.id` NULL
- `requested_by` BIGINT FK -> `users.id`
- `status` VARCHAR(20) (`pending`, `approved`, `rejected`, `converted`)
- `remarks` VARCHAR(255) NULL
- `requested_at` DATETIME
- `created_at`, `updated_at`
- Indexes: (`requested_by`, `status`)

## Receiving and Inventory

### `receivings`
- `id` BIGINT PK
- `receiving_no` VARCHAR(40) UNIQUE
- `purchase_order_id` BIGINT FK -> `purchase_orders.id`
- `received_by` BIGINT FK -> `users.id`
- `received_at` DATETIME
- `status` VARCHAR(20) (`draft`, `posted`)
- `notes` TEXT NULL
- `created_at`, `updated_at`
- Indexes: (`purchase_order_id`, `status`)

### `receiving_items`
- `id` BIGINT PK
- `receiving_id` BIGINT FK -> `receivings.id`
- `product_id` BIGINT FK -> `products.id`
- `received_qty` INT UNSIGNED
- `batch_no` VARCHAR(60) NULL
- `expiry_date` DATE NULL
- `unit_cost` DECIMAL(12,2)
- `created_at` DATETIME NULL
- Indexes: (`receiving_id`, `product_id`), `expiry_date`

### `inventory_stocks`
- `id` BIGINT PK
- `product_id` BIGINT FK -> `products.id` UNIQUE
- `on_hand_qty` INT UNSIGNED
- `reserved_qty` INT UNSIGNED
- `updated_at` DATETIME NULL

### `inventory_movements`
- `id` BIGINT PK
- `product_id` BIGINT FK -> `products.id`
- `reference_type` VARCHAR(30) (`receiving`, `issuance`, `adjustment`, `opening`)
- `reference_id` BIGINT
- `movement_type` VARCHAR(10) (`in`, `out`)
- `quantity` INT UNSIGNED
- `balance_after` INT UNSIGNED
- `movement_at` DATETIME
- `created_by` BIGINT FK -> `users.id`
- `remarks` VARCHAR(255) NULL
- `created_at` DATETIME NULL
- Indexes: (`product_id`, `movement_at`), (`reference_type`, `reference_id`)

## Issuance

### `issuances`
- `id` BIGINT PK
- `issuance_no` VARCHAR(40) UNIQUE
- `issued_to` VARCHAR(120)
- `issued_by` BIGINT FK -> `users.id`
- `status` VARCHAR(20) (`draft`, `posted`)
- `issued_at` DATETIME
- `notes` TEXT NULL
- `created_at`, `updated_at`
- Indexes: (`issued_by`, `status`)

### `issuance_items`
- `id` BIGINT PK
- `issuance_id` BIGINT FK -> `issuances.id`
- `product_id` BIGINT FK -> `products.id`
- `quantity` INT UNSIGNED
- `created_at` DATETIME NULL
- Indexes: (`issuance_id`, `product_id`)

## Integrity and Scaling Notes

- Multi-table writes are wrapped in transactions in repository methods.
- Stock updates always write a movement ledger row for reconciliation.
- Current schema supports straightforward partitioning of `inventory_movements` by date later if needed.
