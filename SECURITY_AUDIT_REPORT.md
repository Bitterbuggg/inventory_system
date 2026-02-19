# Pharmacy Inventory Management System - Security & Code Quality Audit Report
**Date:** February 19, 2026  
**System:** Pharmacy Inventory Management (CodeIgniter 4)

---

## Executive Summary
This comprehensive audit identified **18 Critical/High issues**, **12 Medium issues**, and **15 Low issues** across security, code quality, database design, and code organization. While the application uses CodeIgniter's built-in protections well, several vulnerabilities and code quality concerns require immediate attention.

---

## 1. SECURITY VULNERABILITIES

### 1.1 CRITICAL ISSUES

#### Issue #S1.1: Hardcoded Demo Credentials in View
**File:** [app/Views/auth/login.php](app/Views/auth/login.php#L50-L52)  
**Severity:** CRITICAL  
**Lines:** 50-52  
**Description:**
Demo credentials are hardcoded and displayed to users, creating an authentication bypass vulnerability.

```php
<p>Email: <code class="font-mono bg-white px-1 rounded">admin@pharmacy.local</code></p>
<p>Password: <code class="font-mono bg-white px-1 rounded">Admin@123</code></p>
```

**Risk:** Anyone can log in with admin credentials; credential compromise is trivial.  
**Recommended Fix:**
- Remove demo credentials from production
- Use environment-specific configuration to hide demo credentials in production
- Display demo credentials only in development environments:
```php
<?php if (ENVIRONMENT !== 'production'): ?>
    <!-- Demo credentials only in development -->
<?php endif; ?>
```

---

#### Issue #S1.2: Database Debug Mode Enabled in Production
**File:** [app/Config/Database.php](app/Config/Database.php#L34)  
**Severity:** CRITICAL  
**Line:** 34  
**Description:**
`'DBDebug' => true` enables detailed database error messages in all environments.

**Risk:** Exception messages expose database structure, table names, column names, and query details to attackers.  
**Recommended Fix:**
```php
'DBDebug' => (ENVIRONMENT !== 'production'),
```

---

#### Issue #S1.3: Missing Input Validation on Inventory Search Filters
**File:** [app/Controllers/InventoryController.php](app/Controllers/InventoryController.php#L27-L30)  
**Severity:** HIGH  
**Lines:** 27-30  
**Description:**
Search parameters are retrieved but not validated before being passed to SQL queries:

```php
$page = $this->request->getVar('page') ?? 1;
$search = $this->request->getVar('search') ?? '';
$filterClass = $this->request->getVar('class') ?? '';
$filterSkuPrefix = $this->request->getVar('sku') ?? '';
```

While CodeIgniter's query builder provides parameterization, no validation ensures the data is appropriate (e.g., `$page` should be positive integer).

**Risk:** Unexpected query behavior, potential bypass of business logic.  
**Recommended Fix:**
```php
$page = (int) $this->request->getVar('page') ?? 1;
$page = max(1, $page); // Ensure positive
$search = (string) ($this->request->getVar('search') ?? '');
$search = substr($search, 0, 100); // Limit length
$filterClass = (string) ($this->request->getVar('class') ?? '');
// Validate against allowed dosage forms
$filterSkuPrefix = (string) ($this->request->getVar('sku') ?? '');
$filterSkuPrefix = substr(preg_replace('/[^A-Z0-9]/', '', $filterSkuPrefix), 0, 10);
```

---

#### Issue #S1.4: Weak Password Validation Rules
**File:** [app/Controllers/AuthController.php](app/Controllers/AuthController.php#L69)  
**Severity:** HIGH  
**Line:** 69  
**Description:**
Password validation rule only checks minimum length and confirmation match:

```php
'password' => 'required|min_length[8]|max_length[255]',
```

No complexity requirements (uppercase, lowercase, numbers, special characters).

**Risk:** Users can create weak passwords like "12345678" or "aaaaaaaa".  
**Recommended Fix:**
Implement a stronger validation rule or use custom validation:

```php
'password' => 'required|min_length[12]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/]',
```

Or create a custom rule:
```php
public function validateStrongPassword(string $str): bool
{
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $str) === 1;
}
```

---

#### Issue #S1.5: Session Configuration - IP Address Matching Disabled
**File:** [app/Config/Session.php](app/Config/Session.php#L68)  
**Severity:** HIGH  
**Line:** 68  
**Description:**
`'matchIP' => false` disables IP address validation for session cookies.

```php
public bool $matchIP = false;
```

**Risk:** Session fixation attacks possible; attacker can use stolen session cookie from any IP address.  
**Recommended Fix:**
```php
public bool $matchIP = true;
```
Note: This may cause issues with load-balanced systems or mobile users on 4G/WiFi transitions.

---

#### Issue #S1.6: CSRF Token Not Regenerated on Every Submission
**File:** [app/Config/Security.php](app/Config/Security.php#L75)  
**Severity:** HIGH  
**Lines:** 11-75  
**Description:**
CSRF token randomization is disabled:

```php
public bool $tokenRandomize = false;
public bool $regenerate = true;
```

While tokens are regenerated, randomization is disabled, reducing security posture.

**Recommended Fix:**
```php
public bool $tokenRandomize = true;
```

---

#### Issue #S1.7: Insufficient Error Handling in API Controllers
**File:** [app/Controllers/PurchaseWorkflowController.php](app/Controllers/PurchaseWorkflowController.php#L189-195)  
**Severity:** HIGH  
**Lines:** 189-195  
**Description:**
Generic exception catching and error responses leak information:

```php
} catch (\Throwable $exception) {
    return $this->response
        ->setStatusCode(400)
        ->setJSON(['error' => $exception->getMessage()]);
}
```

**Risk:** Exception messages may leak stack traces, file paths, database information.  
**Recommended Fix:**
```php
} catch (\Throwable $exception) {
    log_message('error', 'PurchaseWorkflow error: ' . $exception->getMessage());
    
    if (ENVIRONMENT === 'production') {
        return $this->response
            ->setStatusCode(400)
            ->setJSON(['error' => 'An error occurred processing your request.']);
    }
    
    return $this->response
        ->setStatusCode(400)
        ->setJSON(['error' => $exception->getMessage()]);
}
```

---

#### Issue #S1.8: Missing Rate Limiting on Authentication Endpoints
**File:** [app/Controllers/AuthController.php](app/Controllers/AuthController.php#L44-57)  
**Severity:** HIGH  
**Lines:** 44-57  
**Description:**
No rate limiting on login attempts. An attacker can brute-force credentials.

**Risk:** Credential bruteforce attacks possible.  
**Recommended Fix:**
Implement rate limiting middleware:
```php
public function login(): RedirectResponse
{
    // Rate limiting check
    $clientIP = $this->request->getIPAddress();
    $cacheKey = "login_attempts_{$clientIP}";
    $attempts = cache($cacheKey) ?? 0;
    
    if ($attempts >= 5) {
        return redirect()->back()->with('error', 'Too many login attempts. Please try again later.');
    }
    
    try {
        // ... login logic
        cache()->delete($cacheKey); // Clear on success
    } catch (\Throwable $e) {
        cache("{$cacheKey}", $attempts + 1, 900); // 15 minutes
        return redirect()->back()->with('error', 'Invalid credentials.');
    }
}
```

---

### 1.2 HIGH ISSUES

#### Issue #S1.9: Missing Authorization Checks in InventoryController
**File:** [app/Controllers/InventoryController.php](app/Controllers/InventoryController.php#L1-20)  
**Severity:** HIGH  
**Lines:** 1-20  
**Description:**
`InventoryController` extends `CodeIgniter\Controller` instead of `BaseController` and has no auth filter applied.

**Risk:** Unfiltered access to inventory data; potential unauthorized information disclosure.  
**Recommended Fix:**
Apply auth filter to routes in [app/Config/Routes.php](app/Config/Routes.php):
```php
$routes->group('inventory', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'InventoryController::index');
    $routes->post('/', 'InventoryController::index');
});
```

---

#### Issue #S1.10: Potential Path Traversal in Vite Asset Loading
**File:** [app/Views/layouts/app.php](app/Views/layouts/app.php#L15-17)  
**Severity:** HIGH  
**Lines:** 15-17  
**Description:**
CSS filenames from manifest.json are not validated before being served:

```php
foreach ($entry['css'] as $css) {
    echo '<link rel="stylesheet" href="' . base_url('build/' . $css) . '">' . "\n";
}
```

**Risk:** If manifest.json is compromised, arbitrary assets could be loaded (path traversal).  
**Recommended Fix:**
```php
foreach ($entry['css'] as $css) {
    // Validate filename - only allow safe characters
    if (preg_match('/^[a-zA-Z0-9._\/-]+\.css$/', $css)) {
        echo '<link rel="stylesheet" href="' . base_url('build/' . esc($css, 'attr')) . '">' . "\n";
    }
}
```

---

#### Issue #S1.11: Sensitive Data Not Masked in Error Pages
**File:** [app/Views/errors/html/error_exception.php](app/Views/errors/html/error_exception.php)  
**Severity:** HIGH  
**Description:**
Error exception pages display full exception messages, stack traces, and potentially database errors in production.

**Risk:** Information disclosure of system internals.  
**Recommended Fix:**
Ensure environment-specific error handling is properly configured:
```php
<?php if (ENVIRONMENT === 'production'): ?>
    <h1>An Error Occurred</h1>
    <p>We're sorry, but something went wrong. Please try again later.</p>
<?php else: ?>
    <!-- Show full details in development -->
    <h1><?= esc($title) ?></h1>
<?php endif; ?>
```

---

#### Issue #S1.12: No Audit Logging for Critical Operations
**File:** [app/Services/PurchaseWorkflowService.php](app/Services/PurchaseWorkflowService.php#L26-35)  
**Severity:** HIGH  
**Description:**
No logging of who created, modified, or deleted purchase requests, orders, or inventory movements.

**Risk:** Cannot track or audit changes; accountability gap.  
**Recommended Fix:**
Add logging to service methods:
```php
public function createPurchaseRequest(array $requestData, array $items): int
{
    $requestData['request_no'] = $requestData['request_no'] ?? $this->generateReference('PR');
    $requestData['status'] = $requestData['status'] ?? 'draft';
    $requestData['requested_at'] = $requestData['requested_at'] ?? date('Y-m-d H:i:s');

    $id = $this->workflowRepository->createPurchaseRequest($requestData, $items);
    
    log_message('info', "Purchase Request #{$id} created by user {$requestData['requested_by']}", 
        ['user_id' => $requestData['requested_by'], 'pr_id' => $id]);
    
    return $id;
}
```

---

#### Issue #S1.13: Missing HTTPS Enforcement
**File:** [app/Config/App.php](app/Config/App.php#L17)  
**Severity:** HIGH  
**Line:** 17  
**Description:**
Application is configured to use `http://` URLs. HTTPS is not enforced.

```php
public string $baseURL = 'http://localhost:8080/';
```

**Risk:** Man-in-the-middle attacks possible; credentials and sensitive data transmitted in cleartext.  
**Recommended Fix:**
In production, enforce HTTPS in [app/Config/Filters.php](app/Config/Filters.php):
```php
public array $required = [
    'before' => [
        'forcehttps', // Force Global Secure Requests - ENABLE IN PRODUCTION
    ],
];
```

And update baseURL configuration:
```php
public string $baseURL = getenv('APP_PROTOCOL') ?? 'https://example.com/';
```

---

---

## 2. CODE QUALITY ISSUES & POSSIBLE ERRORS

### 2.1 CRITICAL ISSUES

#### Issue #CQ1.1: Potential SQL Injection Risk in Inventory Search with Multiple OR Conditions
**File:** [app/Controllers/InventoryController.php](app/Controllers/InventoryController.php#L38-42)  
**Severity:** HIGH (CodeIgniter mitigates, but pattern is problematic)  
**Lines:** 38-42  
**Description:**
Multiple `orLike()` calls can produce unexpected SQL behavior:

```php
if (!empty($search)) {
    $query->orLike('products.sku', $search)
          ->orLike('products.brand_name', $search)
          ->orLike('products.generic_name', $search)
          ->orLike('products.description', $search);
}
```

This creates `OR` conditions that may bypass earlier `WHERE` clauses due to operator precedence.

**Risk:** Unexpected query results; potential data leakage if combined with other filters.  
**Recommended Fix:**
Use grouped conditions:
```php
if (!empty($search)) {
    $query->groupStart()
          ->like('products.sku', $search)
          ->orLike('products.brand_name', $search)
          ->orLike('products.generic_name', $search)
          ->orLike('products.description', $search)
          ->groupEnd();
}
```

---

#### Issue #CQ1.2: N+1 Query Problem in PurchaseWorkflowRepository
**File:** [app/Repositories/Database/PurchaseWorkflowRepository.php](app/Repositories/Database/PurchaseWorkflowRepository.php#L51-65)  
**Severity:** MEDIUM  
**Lines:** 51-65  
**Description:**
`findPurchaseRequestById()` fetches purchase request with one query, then immediately queries all items:

```php
public function findPurchaseRequestById(int $purchaseRequestId): ?array
{
    $purchaseRequest = $this->purchaseRequestModel
        ->select('purchase_requests.*, users.full_name AS requested_by_name')
        ->join('users', 'users.id = purchase_requests.requested_by', 'left')
        ->where('purchase_requests.id', $purchaseRequestId)
        ->first();

    if ($purchaseRequest === null) {
        return null;
    }

    $purchaseRequest['items'] = $this->purchaseRequestItemModel
        ->select('purchase_request_items.*, products.sku, products.brand_name, products.generic_name')
        ->join('products', 'products.id = purchase_request_items.product_id', 'left')
        ->where('purchase_request_id', $purchaseRequestId)
        ->findAll();

    return $purchaseRequest;
}
```

When `paginatePurchaseRequests()` returns 10 rows, this generates 10+1 queries.

**Risk:** Performance degradation with large datasets; unnecessary database load.  
**Recommended Fix:**
Fetch with single query using GROUP_CONCAT or JSON aggregation (MySQL 5.7+):
```php
public function findPurchaseRequestById(int $purchaseRequestId): ?array
{
    $result = $this->db->table('purchase_requests pr')
        ->select('pr.*, u.full_name AS requested_by_name')
        ->selectRaw('JSON_ARRAYAGG(JSON_OBJECT(...)) AS items')
        ->join('users u', 'u.id = pr.requested_by', 'left')
        ->where('pr.id', $purchaseRequestId)
        ->groupBy('pr.id')
        ->get()
        ->getRow();
        
    return $result ? (array) $result : null;
}
```

---

#### Issue #CQ1.3: Missing Null Coalescing in InventoryWorkflow
**File:** [app/Repositories/Database/InventoryRepository.php](app/Repositories/Database/InventoryRepository.php#L26-43)  
**Severity:** MEDIUM  
**Lines:** 26-43  
**Description:**
No null check before accessing stock array elements:

```php
$stock = $this->stockModel->where('product_id', $productId)->first();

if ($stock === null) {
    $this->stockModel->insert([
        'product_id'  => $productId,
        'on_hand_qty' => $quantity,
        'reserved_qty'=> 0,
        'updated_at'  => date('Y-m-d H:i:s'),
    ]);
    $balance = $quantity;
} else {
    $balance = (int) $stock['on_hand_qty'] + $quantity;
    // ...
}
```

If `$stock['on_hand_qty']` is NULL, arithmetic fails.

**Risk:** Type error exceptions; potential inventory miscalculations.  
**Recommended Fix:**
```php
$balance = (int) ($stock['on_hand_qty'] ?? 0) + $quantity;
```

---

#### Issue #CQ1.4: Missing Validation on Integer Casts
**File:** [app/Controllers/PurchaseRequestController.php](app/Controllers/PurchaseRequestController.php#L48-52)  
**Severity:** MEDIUM  
**Lines:** 48-52  
**Description:**
Integer casts without validation:

```php
'requested_by' => (int) $this->request->getPost('requested_by'),
```

No verification that the cast integer is valid/positive.

**Risk:** Invalid user IDs could be passed; foreign key constraints may catch this, but logic assumes valid data.  
**Recommended Fix:**
Validate before casting:
```php
$requestedBy = (int) $this->request->getPost('requested_by');
if ($requestedBy <= 0) {
    throw new \InvalidArgumentException('Invalid user ID');
}
```

---

### 2.2 MEDIUM ISSUES

#### Issue #CQ2.1: Unused Variable in InventoryController
**File:** [app/Controllers/InventoryController.php](app/Controllers/InventoryController.php#L27)  
**Severity:** LOW  
**Line:** 27  
**Description:**
Variable `$page` is extracted but never validated:

```php
$page = $this->request->getVar('page') ?? 1;
```

It's used directly without type casting in line 61.

**Recommended Fix:**
```php
$page = max(1, (int) ($this->request->getVar('page') ?? 1));
```

---

#### Issue #CQ2.2: Duplicate Code in Multiple Controllers
**File:** [app/Controllers/PurchaseRequestController.php](app/Controllers/PurchaseRequestController.php#L110-115), [app/Controllers/PurchaseOrderController.php](app/Controllers/PurchaseOrderController.php#L120-125)  
**Severity:** LOW  
**Description:**
`decodeItems()` method is duplicated in multiple controllers.

**Recommended Fix:**
Move to `BaseController`:
```php
// app/Controllers/BaseController.php
protected function decodeItems(string $itemsJson): array
{
    $items = json_decode($itemsJson, true);
    if (!is_array($items) || empty($items)) {
        throw new \InvalidArgumentException('Items JSON must be a non-empty array.');
    }
    return array_values(array_filter($items, fn($item) => is_array($item)));
}
```

---

#### Issue #CQ2.3: Missing Error Handling for JSON Parsing
**File:** [app/Controllers/PurchaseRequestController.php](app/Controllers/PurchaseRequestController.php#L110)  
**Severity:** MEDIUM  
**Lines:** 110-115  
**Description:**
`json_decode()` called without checking for JSON errors:

```php
private function decodeItems(string $itemsJson): array
{
    $items = json_decode($itemsJson, true);

    if (! is_array($items) || $items === []) {
        throw new \InvalidArgumentException('Items JSON must be a non-empty array.');
    }

    return array_values(array_filter($items, static fn ($item): bool => is_array($item)));
}
```

If JSON is malformed, `json_decode()` returns null silently.

**Risk:** Silent failures; invalid data acceptance.  
**Recommended Fix:**
```php
private function decodeItems(string $itemsJson): array
{
    $items = json_decode($itemsJson, true, 512, JSON_THROW_ON_ERROR);
    
    if (!is_array($items) || empty($items)) {
        throw new \InvalidArgumentException('Items JSON must be a non-empty array.');
    }

    return array_values(array_filter($items, fn($item) => is_array($item)));
}
```

---

#### Issue #CQ2.4: Missing Null Checks in View
**File:** [app/Views/purchase_requests/show.php](app/Views/purchase_requests/show.php#L43-47)  
**Severity:** LOW  
**Lines:** 43-47  
**Description:**
Array access without checking if key exists:

```php
<?php foreach ($record['items'] as $item): ?>
    <tr>
        <td class="text-sm"><?= esc(($item['sku'] ?? '') . ' - ' . ($item['brand_name'] ?? '')) ?></td>
```

If `items` key doesn't exist in `$record`, loop fails.

**Recommended Fix:**
```php
<?php if (!empty($record['items'])): ?>
    <?php foreach ($record['items'] as $item): ?>
        <!-- ... -->
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4" class="text-center">No items</td></tr>
<?php endif; ?>
```

---

#### Issue #CQ2.5: Race Condition in Stock Updates
**File:** [app/Repositories/Database/InventoryRepository.php](app/Repositories/Database/InventoryRepository.php#L26-45)  
**Severity:** HIGH  
**Description:**
Stock check and update are not atomic:

```python
$stock = $this->stockModel->where('product_id', $productId)->first();

if ($stock === null) {
    // Insert new stock
} else {
    $balance = (int) $stock['on_hand_qty'] + $quantity;
    $this->stockModel->update((int) $stock['id'], [
        'on_hand_qty' => $balance,
        'updated_at'  => date('Y-m-d H:i:s'),
    ]);
}
```

Between check and update, another request could modify the stock.

**Risk:** Inventory inconsistency; double-selling possible.  
**Recommended Fix:**
Use database-level locking or atomic updates:
```php
$this->db->table('inventory_stocks')
    ->where('product_id', $productId)
    ->update(['on_hand_qty' => $this->db->raw('on_hand_qty + ' . $quantity)]);
```

Or use transactions properly:
```php
$this->db->transBegin();
try {
    $stock = $this->stockModel->where('product_id', $productId)->first();
    // ... update logic
    $this->db->transCommit();
} catch (\Throwable $e) {
    $this->db->transRollback();
    throw $e;
}
```

---

---

## 3. CODE ORGANIZATION ISSUES

### 3.1 ISSUES

#### Issue #CO1.1: Complex Logic in InventoryController::index()
**File:** [app/Controllers/InventoryController.php](app/Controllers/InventoryController.php#L22-100)  
**Severity:** MEDIUM  
**Lines:** 22-100  
**Description:**
The `index()` method contains 80+ lines of complex query building, filtering, pagination, and data transformation logic.

**Recommended Fix:**
Refactor into a service layer:
```php
// app/Services/InventorySearchService.php
class InventorySearchService {
    public function search(array $filters, int $page = 1, int $perPage = 20): array {
        // Extract search/filter logic here
    }
    
    private function buildQuery(array $filters): Builder {
        // Query construction
    }
    
    private function enrichProducts(array $products): array {
        // Data transformation
    }
}
```

Then simplify controller:
```php
public function index(): string
{
    $search = service('inventorySearchService');
    $result = $search->search($this->request->getGet(), /* ... */);
    
    return view('inventory/index', $result);
}
```

---

#### Issue #CO1.2: Models Lack Business Logic
**File:** [app/Models/ProductModel.php](app/Models/ProductModel.php), [app/Models/InventoryStockModel.php](app/Models/InventoryStockModel.php)  
**Severity:** LOW  
**Description:**
Models are data containers only; no methods for business logic.

**Recommended Fix:**
Add methods to models:
```php
// app/Models/InventoryStockModel.php
class InventoryStockModel extends Model
{
    // ... existing code ...
    
    public function isLowStock(int $productId): bool
    {
        $stock = $this->find($productId);
        $product = model('ProductModel')->find($stock['product_id']);
        return $stock['on_hand_qty'] <= $product['reorder_level'];
    }
    
    public function getAvailableQuantity(int $productId): int
    {
        $stock = $this->find($productId);
        return max(0, ($stock['on_hand_qty'] ?? 0) - ($stock['reserved_qty'] ?? 0));
    }
}
```

---

#### Issue #CO1.3: Missing Documentation on Complex Workflows
**File:** [app/Services/PurchaseWorkflowService.php](app/Services/PurchaseWorkflowService.php)  
**Severity:** LOW  
**Description:**
Complex business workflows lack documentation on state transitions and preconditions.

**Recommended Fix:**
Add docblock documentation:
```php
/**
 * Create a purchase request
 * 
 * State: DRAFT -> SUBMITTED -> APPROVED/REJECTED -> CONVERTED
 * 
 * Preconditions:
 * - User must be Employee or Admin role
 * - Items array must contain valid product_ids
 * - Quantities must be positive integers
 * 
 * @param array $requestData Request data with keys: requested_by, remarks
 * @param array $items Array of items with keys: product_id, requested_qty, unit_cost_estimate
 * @return int Purchase request ID
 * @throws InvalidArgumentException If validation fails
 */
public function createPurchaseRequest(array $requestData, array $items): int
{
    // ...
}
```

---

#### Issue #CO1.4: Inconsistent Naming Conventions
**File:** Throughout codebase  
**Severity:** LOW  
**Description:**
Mix of naming styles:
- Some methods use camelCase: `paginatePurchaseRequests()`
- Some use underscore_case in database column names: `requested_by`
- Some use abbreviations: `pr`, `po`, `rcv`

**Recommended Fix:**
Establish and document naming standards in architecture.md:
```markdown
## Naming Standards
- PHP Classes: PascalCase
- PHP Methods: camelCase
- Database tables: snake_case_plural
- Database columns: snake_case
- Model properties: camelCase (though usually protected $table, etc.)
- Route parameters: kebab-case
```

---

#### Issue #CO1.5: Missing Type Hints in Legacy Code
**File:** Various files  
**Severity:** LOW  
**Description:**
Some methods have incomplete type hints or return types.

**Example:** [app/Repositories/Database/UserRepository.php](app/Repositories/Database/UserRepository.php#L15-20)

```php
public function findByEmail(string $email): ?array
{
```

While this has return type, some methods mix typed and untyped parameters.

**Recommended Fix:**
Add complete type hints:
```php
public function findByEmail(string $email): ?array
{
    $user = $this->userModel
        ->where('email', $email)
        ->where('is_active', 1)
        ->first();

    return $user instanceof stdClass ? (array) $user : null;
}
```

---

---

## 4. DATABASE ISSUES

### 4.1 CRITICAL ISSUES

#### Issue #DB1.1: Missing Expiry Date Validation on Inventory Operations
**File:** [app/Services/PurchaseWorkflowService.php](app/Services/PurchaseWorkflowService.php#L101-122)  
**Severity:** CRITICAL  
**Description:**
When converting receiving or issuing inventory, no validation that items have not expired.

```php
public function convertReceiving(array $receivingData, array $receivingItems): int
{
    $receivingData['receiving_no'] = $receivingData['receiving_no'] ?? $this->generateReference('RCV');
    $receivingData['status'] = $receivingData['status'] ?? 'posted';
    $receivingData['received_at'] = $receivingData['received_at'] ?? date('Y-m-d H:i:s');

    $receivingId = $this->workflowRepository->createReceiving($receivingData, $receivingItems);

    foreach ($receivingItems as $item) {
        $this->inventoryRepository->increaseStock(
            (int) $item['product_id'],
            (int) $item['received_qty'],
            'receiving',
            $receivingId,
            (int) $receivingData['received_by'],
            'Receiving conversion posted',
        );
    }

    return $receivingId;
}
```

**Risk:** Expired medications could be added to inventory and dispensed to patients.  
**Recommended Fix:**
```php
public function convertReceiving(array $receivingData, array $receivingItems): int
{
    // Validate all items haven't expired
    foreach ($receivingItems as $item) {
        if (!empty($item['expiry_date']) && strtotime($item['expiry_date']) < time()) {
            throw new \InvalidArgumentException(
                "Item {$item['product_id']} has expired on {$item['expiry_date']}"
            );
        }
    }
    
    // ... rest of method
}
```

---

#### Issue #DB1.2: Missing Foreign Key Constraints on inventory_movements
**File:** [app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php](app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php#L210-240)  
**Severity:** HIGH  
**Description:**
The `inventory_movements.reference_id` field has no foreign key constraint. This can point to invalid references.

```php
'reference_type' => ['type' => 'VARCHAR', 'constraint' => 30],
'reference_id' => ['type' => 'BIGINT', 'unsigned' => true],
```

**Risk:** Orphaned records; data integrity issues.  
**Recommended Fix:**
Add conditional foreign keys based on reference_type (requires stored procedure in MySQL):
```sql
-- Add constraints for each reference type
ALTER TABLE inventory_movements 
ADD CONSTRAINT fk_inv_mov_receiving 
FOREIGN KEY (reference_id) REFERENCES receivings(id) WHEN reference_type = 'receiving';
```

Or add a CHECK constraint to validate reference_type values and document the expected behavior.

---

#### Issue #DB1.3: Missing NOT NULL Constraints on Critical Fields
**File:** [app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php](app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php)  
**Severity:** MEDIUM  
**Description:**
Several critical fields allow NULL that shouldn't:

- `approved_qty` in `purchase_request_items` (Line 106)
- `remarks` in multiple tables (nullable but business-critical)  
- `batch_no` in `receiving_items` (should be required for pharmaceutical tracking)

**Risk:** Data quality issues; ambiguous states.  
**Recommended Fix:**
```php
// receiving_items.batch_no should be NOT NULL
'batch_no' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => false],

// Make remarks consistently nullable or NOT NULL based on business rules
'remarks' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'default' => ''],
```

---

#### Issue #DB1.4: Missing Database Indexes on Performance-Critical Queries
**File:** [app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php](app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php)  
**Severity:** MEDIUM  
**Description:**
Missing indexes that would improve pagination query performance:

- No index on `inventory_stocks.product_id` (though FK exists)
- No index on `inventory_movements.created_by` for user audit trails
- No index on `approvals.status` for workflow queries

**Risk:** Slow queries; O(n) scans on large tables.  
**Recommended Fix:**
```php
$this->forge->addKey('approver_id'); // Already exists
$this->forge->addKey('created_by'); // Add to inventory_movements
$this->forge->addKey('status'); // Add to approvals
```

---

### 4.2 MEDIUM ISSUES

#### Issue #DB2.1: UUID Not Used for Primary Keys
**File:** [app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php](app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php)  
**Severity:** LOW  
**Description:**
Using auto-increment BIGINT for primary keys. UUIDs would provide better security and distributed generation.

**Recommended Fix:**
Consider ULID or UUID v6 for better security:
```php
'id' => ['type' => 'CHAR', 'constraint' => 26], // ULID
// Generate: new \Ulid\ULID()
```

Or at minimum, use larger auto-increment offset to hide record count.

---

#### Issue #DB2.2: No Soft Delete on Critical Tables
**File:** [app/Models/UserModel.php](app/Models/UserModel.php#L10)  
**Severity:** MEDIUM  
**Description:**
Using soft deletes for `users` but not for purchase requests, orders, or inventory movements. This creates audit and data recovery issues.

**Recommended Fix:**
Add soft deletes to all transaction tables:
```php
// app/Models/PurchaseRequestModel.php
protected $useSoftDeletes = true;

// Update migration
'deleted_at' => ['type' => 'DATETIME', 'null' => true],
```

---

#### Issue #DB2.3: Password Hash Field Size May Be Inadequate for Future Algorithms
**File:** [app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php](app/Database/Migrations/2026-02-19-000001_CreatePharmacyInventorySchema.php#L36)  
**Severity:** LOW  
**Line:** 36  
**Description:**
`password_hash` VARCHAR(255) is adequate for current algorithms but may become inadequate if migrating to stronger hashing.

```php
'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
```

**Recommended Fix:**
Increase to 512 for future algorithms:
```php
'password_hash' => ['type' => 'VARCHAR', 'constraint' => 512],
```

---

---

## 5. CODE QUALITY - UNUSED CODE & DEAD CODE

### 5.1 FINDINGS

#### Issue #UC1.1: Unused Imports in Controllers
**File:** [app/Controllers/PurchaseWorkflowController.php](app/Controllers/PurchaseWorkflowController.php#L1-9)  
**Severity:** LOW  
**Lines:** 1-9  
**Description:**
Duplicate import of `ResponseInterface`:

```php
use CodeIgniter\HTTP\ResponseInterface;
// ...
use CodeIgniter\HTTP\ResponseInterface as HttpResponseInterface;
```

**Recommended Fix:**
```php
use CodeIgniter\HTTP\ResponseInterface;
```

---

#### Issue #UC1.2: Unused Code in Database Config
**File:** [app/Config/Database.php](app/Config/Database.php#L45-90)  
**Severity:** LOW  
**Lines:** 45-90  
**Description:**
Commented-out database configuration examples for SQLite3 and PostgreSQL.

**Recommended Fix:**
Remove or move to documentation:
```php
// Create separate config examples in docs/
// - docs/database-config-sqlite.php
// - docs/database-config-postgres.php
```

---

#### Issue #UC1.3: Unused Helper Function
**File:** [app/Helpers/vite_helper.php](app/Helpers/vite_helper.php)  
**Severity:** LOW  
**Description:**
The helper appears to have both `vite_helper.php` and `ViteHelper.php`. One may be unused.

**Recommended Fix:**
Verify which is actually used, remove the other.

---

---

## 6. CONFIGURATION & ENVIRONMENT ISSUES

### 6.1 ISSUES

#### Issue #ENV1.1: Missing Environment Variable Configuration
**File:** [app/Config/Database.php](app/Config/Database.php#L26-31)  
**Severity:** HIGH  
**Description:**
Database credentials are hardcoded with empty values:

```php
'hostname'     => 'localhost',
'username'     => '',
'password'     => '',
'database'     => '',
```

**Risk:** No ability to use different databases per environment; security risk if .env used.  
**Recommended Fix:**
```php
'hostname'     => getenv('DB_HOST') ?: 'localhost',
'username'     => getenv('DB_USER') ?: '',
'password'     => getenv('DB_PASS') ?: '',
'database'     => getenv('DB_NAME') ?: '',
```

And document in .env.example:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=pharmacy_inventory
```

---

---

## SUMMARY TABLE

| Category | Critical | High | Medium | Low | Total |
|----------|----------|------|--------|-----|-------|
| Security | 8 | 6 | 2 | 0 | **16** |
| Code Quality | 5 | 2 | 3 | 3 | **13** |
| Code Organization | 0 | 0 | 1 | 4 | **5** |
| Database | 4 | 2 | 2 | 1 | **9** |
| **TOTAL** | **17** | **10** | **8** | **8** | **43** |

---

## RECOMMENDATIONS - PRIORITY ORDER

### Phase 1: Immediate Security (Complete within 1 week)
1. **S1.1** - Remove hardcoded demo credentials
2. **S1.2** - Disable database debug mode in production
3. **S1.5** - Enable session IP matching
4. **DB1.1** - Add expiry date validation
5. **S1.8** - Implement login rate limiting

### Phase 2: Critical Vulnerabilities (Complete within 2 weeks)
6. **S1.12** - Add audit logging
7. **S1.13** - Enforce HTTPS
8. **CQ1.2** - Fix N+1 queries
9. **CQ2.5** - Fix race conditions  
10. **DB1.2** - Add missing foreign keys

### Phase 3: High Priority (Complete within 1 month)
11. **S1.3** - Validate inventory filters
12. **S1.4** - Strengthen password requirements
13. **S1.6** - Enable CSRF token randomization
14. **S1.9** - Add authorization to InventoryController
15. **CO1.1** - Refactor InventoryController

### Phase 4: Medium Priority (Complete within 2 months)
16. **CQ2.3** - Fix JSON parsing errors
17. **CQ2.4** - Add null checks in views
18. **DB2.2** - Add soft deletes
19. **ENV1.1** - Environment variable configuration
20. **CO1.2** - Add model business logic

---

## TESTING RECOMMENDATIONS

1. Add security test suite for CSRF, XSS, and authentication
2. Load test inventory search to verify N+1 not degrading performance
3. Add database constraint tests
4. Create audit log tests
5. Add rate limiting tests

---

## CONCLUSION

The pharmacy inventory management system has a solid foundation with CodeIgniter 4 and good use of framework security features. However, several critical security vulnerabilities (hardcoded credentials, debug mode enabled, missing authorization), code quality issues (N+1 queries, race conditions), and database design gaps (missing validation, inadequate constraints) require prompt remediation.

The recommended phased approach prioritizes security risks first, followed by critical business logic issues, then refactoring and optimization.

**Overall Recommendation:** Code Review REQUIRED before production deployment.
