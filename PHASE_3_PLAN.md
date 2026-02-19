# Phase 3 - Code Quality & Optimization

**Date Started:** 2026-02-19  
**Current Status:** 🚀 IN PROGRESS

## Overview

Phase 3 focuses on **medium-priority code quality improvements** and **database optimization**, improving maintainability, performance, and future-proofing without changing core functionality.

**Target Issues:** 8 Medium Priority + 8 Low Priority issues  
**Estimated Duration:** 4-6 hours  
**Success Criteria:** All tests pass + no regressions + improved code quality metrics

---

## 📋 Phase 3 Task Breakdown

### Priority 1: JSON Parsing & Error Handling (HIGH IMPACT)

#### CQ2.3: Add JSON Error Handling in PurchaseRequestController
- **File:** `app/Controllers/PurchaseRequestController.php`
- **Current Issue:** `json_decode()` fails silently on malformed JSON
- **Fix:** Use `JSON_THROW_ON_ERROR` flag
- **Impact:** Prevents invalid data acceptance; improves reliability
- **Status:** ⏳ PENDING

#### CQ2.2: Remove Duplicate `decodeItems()` Method
- **Files:** `app/Controllers/PurchaseRequestController.php`, `app/Controllers/PurchaseOrderController.php`
- **Current Issue:** Duplicate method in two controllers
- **Fix:** Move to `BaseController` as protected method
- **Impact:** Single source of truth; easier maintenance
- **Status:** ⏳ PENDING

#### UC1.1: Clean Up Unused Imports
- **File:** `app/Controllers/PurchaseWorkflowController.php`
- **Current Issue:** Duplicate ResponseInterface import
- **Fix:** Remove duplicate, keep single import
- **Impact:** Cleaner code
- **Status:** ⏳ PENDING

---

### Priority 2: Database Schema Enhancements (MEDIUM IMPACT)

#### DB1.3: Add NOT NULL Constraints & Update Field Definitions
- **Files:** Database migration
- **Current Issue:** `receiving_items.batch_no` should be NOT NULL; inconsistent remarks field
- **Fix:** Create new migration to add constraints
- **Impact:** Better data integrity
- **Status:** ⏳ PENDING

#### DB1.4: Add Performance Indexes
- **Files:** Database migration  
- **Current Issue:** Missing indexes on `inventory_movements.created_by`, `approvals.status`
- **Fix:** Create migration to add strategic indexes
- **Impact:** Faster queries; better pagination performance
- **Status:** ⏳ PENDING

#### DB2.2: Add Soft Deletes to Critical Tables
- **Files:** Multiple models and migration
- **Current Issue:** `deleted_at` missing from transaction tables (purchase requests, orders, etc.)
- **Fix:** Create migration + update models
- **Impact:** Better audit trail; data recovery capability
- **Status:** ⏳ PENDING

---

### Priority 3: Code Refactoring (MEDIUM IMPACT)

#### CO1.1: Refactor InventoryController Complex Logic
- **File:** `app/Controllers/InventoryController.php`
- **Current Issue:** 80+ lines in `index()` method (search, filter, pagination, data transform)
- **Fix:** Extract to `InventorySearchService`
- **Impact:** Improved readability; testability; reusability
- **Status:** ⏳ PENDING

#### CQ2.1: Validate Integer Parameters
- **File:** `app/Controllers/InventoryController.php`
- **Current Issue:** `$page` not validated before use
- **Fix:** Add type casting and bounds checking
- **Impact:** Prevents unexpected behavior
- **Status:** ⏳ PENDING

#### CQ2.4: Add Null Checks in Views
- **Files:** `app/Views/purchase_requests/show.php` and related
- **Current Issue:** Array access without isset/empty checks
- **Fix:** Wrap loops in conditional checks
- **Impact:** Prevents PHP notices; better error handling
- **Status:** ⏳ PENDING

---

### Priority 4: Model Business Logic (LOW IMPACT)

#### CO1.2: Add Business Logic Methods to Models
- **Files:** `app/Models/InventoryStockModel.php`, `app/Models/ProductModel.php`
- **Current Issue:** Models are data containers; business logic in controllers
- **Fix:** Add methods: `isLowStock()`, `getAvailableQuantity()`, etc.
- **Impact:** Better OOP practices; easier testing
- **Status:** ⏳ PENDING

#### CQ1.3: Add Null Coalescing in InventoryRepository
- **File:** `app/Repositories/Database/InventoryRepository.php` (Already done in Phase 2)
- **Status:** ✅ COMPLETED

---

### Priority 5: Code Cleanup (LOW IMPACT)

#### UC1.2: Move Commented Config to Documentation
- **File:** `app/Config/Database.php`
- **Current Issue:** Commented-out SQLite/PostgreSQL configs clutter the file
- **Fix:** Move to separate documentation files
- **Impact:** Cleaner config file
- **Status:** ⏳ PENDING

#### UC1.3: Identify and Remove Unused Helpers
- **File:** `app/Helpers/`
- **Current Issue:** Potentially duplicate vite_helper.php and ViteHelper.php
- **Fix:** Audit, keep one, document in architecture.md
- **Impact:** Reduces confusion; smaller codebase
- **Status:** ⏳ PENDING

---

## 🎯 Implementation Order

1. **Batch 1** - JSON error handling + import cleanup (quick wins)
2. **Batch 2** - Refactor InventoryController + service extraction  
3. **Batch 3** - Database migrations (constraints + indexes + soft deletes)
4. **Batch 4** - Model business logic + null checks in views
5. **Batch 5** - Code cleanup + final testing

---

## 📊 Expected Outcomes

- ✅ All 21 tests still passing
- ✅ Improved code readability (lower cyclomatic complexity)
- ✅ Better data integrity (constraints)
- ✅ Faster queries (indexes)
- ✅ Audit capability (soft deletes)
- ✅ Cleaner codebase (no dead code/comments)

---

## 🔗 Tracking

**Start Time:** 2026-02-19 03:30 UTC  
**Tasks:** 11 items  
**Completion Target:** 5-6 hours  

---

*Phase 3: Making the code production-ready for scaling and maintenance*
