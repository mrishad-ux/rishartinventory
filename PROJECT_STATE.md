# FlavorDesk - PROJECT_STATE.md
_Last updated: 15 Mar 2026 (WhatsApp Screenshot Share, Closing Display Fix)_

---

## Project Overview
- **App:** FlavorDesk — Restaurant Management System for a shawarma/wrap fast food outlet
- **Stack:** Laravel, XAMPP MySQL, Tailwind CDN, Blade templates
- **Dev Environment:** Windows, XAMPP, VSCode + OpenCode (MCP enabled)
- **Project Path:** `C:\Users\mrish\Downloads\RestaurantManager\FlavorDesk`

---

## Database & Models

### Tables & Models
| Table | Model | File |
|-------|-------|------|
| `inventory_items` | `InventoryItem` | `app/Models/InventoryItem.php` |
| `inventory_logs` | `InventoryLog` | `app/Models/InventoryLog.php` |
| `expenses` | `Expense` | `app/Models/Expense.php` |
| `sales` | `Sale` | `app/Models/Sale.php` |
| `staff` | `Staff` | `app/Models/Staff.php` |
| `payroll` | `Payroll` | `app/Models/Payroll.php` |
| `suppliers` | `Supplier` | `app/Models/Supplier.php` |
| `platform_settlements` | `PlatformSettlement` | `app/Models/PlatformSettlement.php` |
| `users` | `User` | `app/Models/User.php` |
| `expense_categories` | `ExpenseCategory` | `app/Models/ExpenseCategory.php` |

### inventory_logs columns
| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | PK |
| `inventory_item_id` | FK | → inventory_items |
| `log_date` | date | Unique per item per day |
| `opening` | decimal(10,2) | Default 0 |
| `purchased` | decimal(10,2) | Default 0 |
| `total` | decimal(10,2) | Stored as opening + purchased |
| `consumption` | decimal(10,2) | Default 0 |
| `wastage` | decimal(10,2) | Default 0 |
| `closing` | decimal(10,2) | Regular column (not computed) — manually saved |
| `mayo_oil_qty` | decimal nullable | Mayo items only |
| `mayo_milk_qty` | decimal nullable | Mayo items only |
| `mayo_bottles` | decimal nullable | Mayo items only |
| `notes` | text nullable | |
| `gas_changed` | boolean | Default false |

### platform_settlements columns
| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | PK |
| `platform` | enum(swiggy, zomato) | Platform name |
| `period_from` | date | Start of billing week |
| `period_to` | date | End of billing week |
| `expected_credit_date` | date | Wednesday after period ends |
| `actual_credit_date` | date nullable | When amount was actually received |
| `gross_amount` | decimal(10,2) | Sum of gross sales for period |
| `estimated_commission` | decimal(10,2) | 31% of gross |
| `estimated_net` | decimal(10,2) | 69% of gross |
| `actual_amount_received` | decimal(10,2) nullable | Filled when credited |
| `actual_commission` | decimal(10,2) nullable | Actual deduction |
| `status` | enum(pending, received, disputed) | Default: pending |
| `notes` | text nullable | |

### InventoryItem Categories
```php
'shawarma_marination' => 'MARINATION'
'mayo_masala_sauces'  => 'Mayo, Masala & Sauces'
'chicken_fish'        => 'Chicken & Fish'
'bun_bakery'          => 'Bun, Bakery & Grocery'
'other'               => 'Other'
```

---

## Routes (web.php)
```
GET  /                          → DashboardController@index
GET  /dashboard                 → DashboardController@index
Resource: suppliers, inventory, expenses, sales, staff, payroll
GET  /inventory-daily           → InventoryController@dailyEntry
POST /inventory/{id}/log        → InventoryController@saveLog     ← SAVE ENTRY HERE
GET  /inventory-history         → InventoryController@history
GET  /inventory-import          → InventoryController@importForm
POST /inventory-import          → InventoryController@importStore
POST /inventory-reorder         → InventoryController@reorder
GET  /inventory-low-stock       → InventoryController@getLowStock    ← AJAX: fetch low stock items
POST /expense-categories        → ExpenseCategoryController@store     ← AJAX: create custom category
GET  /accounts                  → AccountsController@index         ← ACCOUNTS DASHBOARD
GET  /backup                   → BackupController@index          ← BACKUP & RESTORE PAGE
POST /backup/download          → BackupController@backup         ← DOWNLOAD SQL BACKUP
POST /backup/restore           → BackupController@restore        ← RESTORE FROM SQL FILE
```

---

## Controllers
| Controller | File |
|-----------|------|
| `DashboardController` | `app/Http/Controllers/DashboardController.php` |
| `InventoryController` | `app/Http/Controllers/InventoryController.php` |
| `ExpenseController` | `app/Http/Controllers/ExpenseController.php` |
| `SaleController` | `app/Http/Controllers/SaleController.php` |
| `StaffController` | `app/Http/Controllers/StaffController.php` |
| `PayrollController` | `app/Http/Controllers/PayrollController.php` |
| `SupplierController` | `app/Http/Controllers/SupplierController.php` |
| `AccountsController` | `app/Http/Controllers/AccountsController.php` |
| `SettlementController` | `app/Http/Controllers/SettlementController.php` |
| `ExpenseCategoryController` | `app/Http/Controllers/ExpenseCategoryController.php` |
| `BackupController` | `app/Http/Controllers/BackupController.php` |

---

## Features Status
| Feature | Status |
|---------|--------|
| Item Master (CRUD) | ✅ Done |
| Daily Stock Entry | ✅ Done |
| Stock History | ✅ Done |
| CSV Import | ✅ Done |
| Item Reorder (drag) | ✅ Done |
| Suppliers | ✅ Done |
| Expenses | ✅ Done (basic) |
| Sales | ✅ Done (basic) |
| Staff | ✅ Done (basic) |
| Payroll | ✅ Done (basic) |
| Dashboard | ✅ Done (with Recent Sales fixes) |
| **Accounts Dashboard (P&L, Payables, Receivables)** | ✅ Done (11 Mar 2026) |
| **Petty Cash & Cleaning Materials categories added to Expenses** | ✅ Done (12 Mar 2026) |
| **Split Online Sales into Swiggy & Zomato** | ✅ Done (11 Mar 2026) |
| **Platform Settlements Tracking System** | ✅ Done (11 Mar 2026) |
| **Platform Settlements: Edit functionality & Variance display** | ✅ Done (12 Mar 2026) |
| **Accounts Dashboard: Bal Cash+Account calculation update** | ✅ Done (12 Mar 2026) |
| Closing → Next Day Opening carry-forward (initial save) | ✅ Fixed (11 Mar 2026) |
| **Re-edit closing → auto-update next day opening with blue highlight** | ✅ Done (11 Mar 2026) |
| **Closing < Min Qty → Red/Yellow highlight & warning icon** | ✅ Fixed (11 Mar 2026) |
| **Closing value not saving to DB (closing not in $fillable)** | ✅ Fixed (11 Mar 2026) |
| **Gas Cylinder Tracking in Daily Entry** | ✅ Done (12 Mar 2026) |
| **Inventory Item Master Table UI Fixes** | ✅ Done (12 Mar 2026) |
| **WhatsApp Purchase Alert (server-side fetch)** | ✅ Done (12 Mar 2026) |
| **Expense Categories (fixed list + dynamic custom)** | ✅ Done (12 Mar 2026) |
| **Dark Glass Theme - Dashboard** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Daily Stock Entry** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Expenses (create, edit)** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Suppliers (index, create, edit)** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Staff (index, create, edit)** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Payroll (index, create, edit)** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Inventory (index, create, edit, history)** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Accounts** | ✅ Done (13 Mar 2026) |
| **Dark Glass Theme - Settlements** | ✅ Done (13 Mar 2026) |
| **Settlement Duplicate Prevention & Cleanup** | ✅ Done (13 Mar 2026) |
| **Settlement Edit Modal Fix (auto-open bug)** | ✅ Done (13 Mar 2026) |
| **Backup & Restore Feature** | ✅ Done (13 Mar 2026) |
| **Accounts Modal Fix (auto-open bug)** | ✅ Done (13 Mar 2026) |
| **Item Master Button Fix** | ✅ Done (13 Mar 2026) |
| **InventoryItemSeeder Created** | ✅ Done (13 Mar 2026) |
| **Daily Inventory Save Error Fix** | ✅ Done (13 Mar 2026) |
| **Bulk Sales Entry (Redesigned Record Sale)** | ✅ Done (13 Mar 2026) |
| **Sales Table Column Alignment Fix** | ✅ Done (13 Mar 2026) |
| **Sales: Collapsible TODAY'S SALES & THIS MONTH with 3-column grid** | ✅ Done (14 Mar 2026) |
| **Sales: ONLINE PENDING & ONLINE CREDITED cards** | ✅ Done (14 Mar 2026) |
| **Sales: CREDIT SALES card for today** | ✅ Done (14 Mar 2026) |

---

## Completed Fixes (11-12 Mar 2026)

### ✅ FIX: Closing carry-forward on initial save
- Added carry-forward block in `saveLog()` in `InventoryController`
- When a day's log is saved, next day's `opening` is auto-created/updated with today's `closing`
- If next day log already exists, only `opening` is updated — existing data is safe

### ✅ FIX: Blue Highlight for Auto-Pushed Opening from Previous Day
- Added `opening_source` column to `inventory_logs` table (migration)
- Updated `InventoryLog` model to include `opening_source` in `$fillable`
- Updated `InventoryController@saveLog()`:
  - Sets `opening_source = 'manual'` only when staff explicitly edits the opening field
  - Carry-forward logic sets `opening_source = 'auto'` for auto-pushed openings
  - Only updates next day's opening/closing if no activity exists (purchased = 0, consumption = 0, wastage = 0)
- Updated `daily.blade.php`:
  - Added `opening_source` hidden field to form
  - Updated @php block to compute `$openingClass`, `$openingTitle`, `$openingReadonly`
  - Updated input field to use color classes based on `opening_source`
  - Updated `toggleOpeningLock()` JS to handle blue highlighting for auto-pushed openings
  - Updated `saveRow()` JS to preserve opening source colors after save

### ✅ FIX: Closing value not saving to DB (closing not in $fillable)
- **Issue:** `closing` column was not in `InventoryLog::$fillable`, so it was ignored by Eloquent
- **Fix:** Added `closing` to `$fillable` array in `app/Models/InventoryLog.php`
- **Impact:** Closing values are now properly saved to database and carry-forward logic works correctly

### ✅ FIX: Color change & warning icon when closing < minimum
- **Issue:** No visual indication when stock is below minimum quantity
- **Fix:** Updated `resources/views/inventory/daily.blade.php`:
  - Added conditional row classes based on closing vs minimum_stock
  - `closing < minimum_stock` → `bg-red-100 border-l-4 border-red-500`
  - `minimum_stock <= closing <= minimum_stock * 1.2` → `bg-yellow-100 border-l-4 border-yellow-400`
  - Added `⚠️` warning icon in Item column when `closing < minimum_stock`
  - Added "Low" (red) and "Warning" (yellow) badges to indicate stock levels

### ✅ FIX: Added console logging for debugging
- **Backend:** Added `Log::info()` statements in `InventoryController@saveLog()` to trace:
  - Input values (item_id, date, opening, purchased, closing, etc.)
  - Carry-forward logic execution
  - Next day log creation/update
- **Frontend:** Added `console.log()` in `saveRow()` JavaScript to trace form data before submission
- **Model:** Added logging in `getClosingAttribute()` to trace closing value calculation

### ✅ NEW: Platform Settlements Edit & Variance
- **Controller:** Added `update()` method to `SettlementController` to handle editing settlements
- **Route:** Added `PUT /settlements/{settlement}` → `settlements.update`
- **Views:** Updated `settlements/index.blade.php` with:
  - "Edit" button for all settlement rows (pending, received, disputed)
  - "Edit Settlement" modal with fields for actual amount, credit date, notes, and status
  - Variance display for received rows showing difference between estimated and actual
- **Features:**
  - Edit settlements even after marking as received
  - Update status to pending, received, or disputed
  - Variance shown as +₹X (green), -₹X (red), or ✓ exact (gray)

### ✅ FIX: Dashboard Recent Sales Issues
- **Issue:** Recent Sales table showing blank item names and ₹0.00 amounts
- **Fix in DashboardController:**
  - Updated `recentSales` query to: `Sale::orderBy('sale_date', 'desc')->orderBy('created_at', 'desc')->limit(5)->get()`
  - Ensured `sale_date` is cast as date in Sale model (already existed)
- **Fix in dashboard.blade.php:**
  - Changed `item_name` to display readable sale_type labels using @php switch statement:
    - 'cash' → 'Cash Sale'
    - 'gp' → 'GPay/UPI'
    - 'swiggy' → 'Swiggy'
    - 'zomato' → 'Zomato'
  - Changed `sale_date` to `{{ $sale->sale_date->format('d M Y') }}`
  - Changed `total_amount` to `net_amount` (₹{{ number_format($sale->net_amount, 2) }})

### ✅ NEW: Accounts Dashboard
- **Controller:** `AccountsController` with `index()` method
- **Route:** `GET /accounts` → `accounts.index`
- **Sidebar:** Added "Accounts" link after Payroll with 💰 icon
- **Features:**
  - **Income:** Cash, GP, and Online sales summaries with pending settlements
  - **Expenses:** Grouped by category, with paid/unpaid/total breakdown
  - **Payables:** Supplier outstanding balances and unpaid bills with due dates
  - **Receivables:** Pending online settlements with expected settlement dates
  - **Net Profit:** Calculated on both cash basis and accrual basis
  - **Period Filtering:** Daily, Weekly, and Monthly views with date pickers
- **Helper Method:** `getDateRange()` calculates date ranges based on period type

### ✅ NEW: Petty Cash & Cleaning Materials Categories
- **Category:** Added 'petty_cash' and 'cleaning_materials' options to Expense category dropdown
- **Files Updated:**
  - `resources/views/expenses/create.blade.php` — Added Petty Cash and Cleaning Materials options
  - `resources/views/expenses/edit.blade.php` — Added Petty Cash and Cleaning Materials options
  - `resources/views/expenses/index.blade.php` — Added gray badge styling for petty_cash category

### ✅ NEW: Platform Settlements Tracking System
- **Migration:** Created `2026_03_11_180042_create_platform_settlements_table.php`
- **Model:** `app/Models/PlatformSettlement.php` with `getCommissionPercentAttribute()` helper
- **Controllers:**
  - `SettlementController` - Dedicated settlement management with `index()`, `generate()`, `markReceived()`, `update()`
  - `AccountsController` - Updated to display pending/recent settlements with variance calculations
- **Routes:**
  - `GET /settlements` → `settlements.index`
  - `POST /settlements/generate` → `settlements.generate`
  - `POST /settlements/{settlement}/mark-received` → `settlements.markReceived`
  - `PUT /settlements/{settlement}` → `settlements.update`
- **Views:**
  - `resources/views/settlements/index.blade.php` - Full settlement management interface
  - `resources/views/accounts/index.blade.php` - Updated to show Platform Settlements section with:
    - Pending Settlements table with overdue highlighting
    - Recently Received table with variance and commission % tracking
    - "Mark Received" modal with Alpine.js
    - "Auto-generate from Sales data" button
- **Features:**
  - Weekly settlement periods (Swiggy: Sun-Sat, Zomato: Mon-Sun)
  - Expected credit date calculated as next Wednesday after period ends
  - Estimated commission at 31%, estimated net at 69%
  - Status tracking: pending, received, disputed
  - Actual commission calculated when marked as received
  - Variance tracking (actual vs estimated) with color coding
  - Overdue highlighting in expected credit dates
  - **Edit Settlement Modal:** Update actual amount, credit date, notes, and status
  - **Variance Display:** Shows +₹X (green), -₹X (red), or ✓ exact (gray) for received settlements

### ✅ NEW: Split Online Sales into Swiggy and Zomato
- **Migration:** Created `2026_03_11_175017_update_sales_type_swiggy_zomato.php` to:
  - Modify `sale_type` enum column to include 'swiggy' and 'zomato'
  - Convert existing 'online' sales to 'swiggy' or 'zomato' based on platform
  - Default any remaining 'online' records to 'swiggy'
- **Sale Type Options:** Changed from 'online' to 'swiggy' and 'zomato' in dropdowns
- **Badge Colors:**
  - Swiggy: Orange (`bg-orange-100 text-orange-700`)
  - Zomato: Red (`bg-red-100 text-red-700`)
  - Cash: Blue (`bg-blue-100 text-blue-700`)
  - Google Pay: Green (`bg-green-100 text-green-700`)
- **Files Updated:**
  - `resources/views/sales/create.blade.php` — Updated sale_type dropdown and Alpine.js logic
  - `resources/views/sales/edit.blade.php` — Updated sale_type dropdown and Alpine.js logic
  - `resources/views/sales/index.blade.php` — Updated badge colors
  - `app/Http/Controllers/SaleController.php` — Updated validation rules and online sale logic
  - `app/Http/Controllers/AccountsController.php` — Updated to use `whereIn(['swiggy', 'zomato'])` instead of 'online'

### ✅ UPDATED: Accounts Dashboard Balance Calculation
- **Net Profit Renamed:** "Net Profit" changed to "Bal Cash+Account" in summary card and P&L footer
- **New Formula:** `$bal_cash_account = $cash_total + $gp_total + $online_credited - $paid_total`
  - `$cash_total`: Sum of net_amount from sales where sale_type = 'cash'
  - `$gp_total`: Sum of net_amount from sales where sale_type = 'gp'
  - `$online_credited`: Sum of actual_amount_received from platform_settlements where status = 'received' AND actual_credit_date within period
  - `$paid_total`: Sum of amount from expenses where status = 'paid'
- **P&L Footer Update:** "Cash Basis" row changed to "Cash + Settled Online" with detailed formula display
- **Summary Card Update:** Third card now shows "BAL CASH+ACCOUNT" with subtext "Cash + GPay + Credited Online"
- **Files Updated:**
  - `app/Http/Controllers/AccountsController.php` — Updated calculation logic
  - `resources/views/accounts/index.blade.php` — Updated labels, formula display, and card layout

### Colour Logic Implemented:
**Inventory Opening Colors:**
- 🟡 Yellow (`bg-amber-100 border-amber-400`) = manually edited by staff (`opening_source = 'manual'`)
- 🔵 Blue (`bg-blue-100 border-blue-400`) = auto-pushed from previous day closing (`opening_source = 'auto'`)
- ⬜ Gray (`bg-gray-100 border-gray-200`) = default / untouched (`opening_source = 'default'`)
**Inventory Stock Level Colors:**
- 🔴 Red (`bg-red-100 border-l-4 border-red-500`) = closing < minimum_stock
- 🟡 Yellow (`bg-yellow-100 border-l-4 border-yellow-400`) = minimum_stock <= closing <= minimum_stock * 1.2
**Sales Badge Colors:**
- 🔵 Blue (`bg-blue-100 text-blue-700`) = Cash
- 🟢 Green (`bg-green-100 text-green-700`) = Google Pay
- 🟠 Orange (`bg-orange-100 text-orange-700`) = Swiggy
- 🔴 Red (`bg-red-100 text-red-700`) = Zomato

---

## Pending Features

(None - all features completed)

---

## Migrations (in order)
```
0001_01_01_000000_create_users_table
0001_01_01_000001_create_cache_table
0001_01_01_000002_create_jobs_table
2025_01_01_000010_modify_inventory_items
2025_01_01_000011_create_inventory_logs
2026_03_09_061933_create_suppliers_table
2026_03_09_062018_create_inventory_items_table
2026_03_09_062033_create_expenses_table
2026_03_09_062038_create_sales_table
2026_03_09_062042_create_staff_table
2026_03_09_062048_create_payroll_table
2026_03_10_161536_change_closing_to_regular_column_in_inventory_logs
2026_03_10_164519_add_sort_order_to_inventory_items
2026_03_11_012017_add_opening_source_to_inventory_logs
2026_03_11_175017_update_sales_type_swiggy_zomato
2026_03_11_180042_create_platform_settlements_table
2026_03_11_195004_add_gas_changed_to_inventory_logs
2026_03_12_140549_create_expense_categories_table
2026_03_13_122345_add_role_to_users_table
```

---

## Dev Notes
- `closing` column was originally a stored/computed column, changed to regular column on 10 Mar 2026
- `closing` column must be included in `$fillable` array of `InventoryLog` model to be saved to DB
- `total` column is still a stored computed column (`opening + purchased`)
- Mayo items have extra fields: `mayo_oil_qty`, `mayo_milk_qty`, `mayo_bottles`
- One log per item per day enforced by unique constraint on `(inventory_item_id, log_date)`
- Opening highlight colours: 🟡 Yellow = manually edited by staff, 🔵 Blue = auto-pushed from previous day closing, ⬜ Gray = default/untouched
- Stock level colours: 🔴 Red = closing < minimum_stock, 🟡 Yellow = minimum_stock <= closing <= minimum_stock * 1.2
- `opening_source` column on `inventory_logs` tracks the source of the opening value: 'default', 'auto', or 'manual'
- Carry-forward logic lives in `InventoryController@saveLog()` — always update this when changing save behaviour
- Debug logging added to backend (`Log::info`) and frontend (`console.log`) for troubleshooting inventory issues
- **Dashboard:** Recent Sales shows sale_type as readable labels (Cash Sale, GPay/UPI, Swiggy, Zomato). Uses `net_amount` instead of non-existent `total_amount` column.
- **Accounts Dashboard:** Aggregates data from Sales, Expense, and Supplier models. Uses `expense_date` for date filtering (not `date`).
- **Expenses:** Added 'petty_cash' and 'cleaning_materials' category options with gray badge styling in index view.
- **Sales:** Split 'online' sale_type into 'swiggy' and 'zomato'. Use `whereIn('sale_type', ['swiggy', 'zomato'])` for online sales queries.
- **Settlements:** Swiggy uses Sun-Sat weeks, Zomato uses Mon-Sun weeks. Expected credit date is next Wednesday after period ends. Duplicate prevention uses `firstOrCreate()`.
- **Settlement Edit Modal:** Uses inline `style="display:none;"` for hidden state, JavaScript toggles `style.display = 'flex'` to show. Added click-outside-to-close functionality.
- **Bal Cash+Account Formula:** `Cash + GP + Credited Online - Paid Expenses`. Credited Online is sum of `actual_amount_received` from `platform_settlements` where status='received' and `actual_credit_date` within period.
- **Gas Tracking:** Added `gas_changed` column to `inventory_logs` table. Daily entry page shows gas cylinder status with "Last changed" info and warning if > 4 days since change.
- **Inventory Items:** Added Sunflower Oil and Palm Oil to 'other' category (verified unique). Fixed duplicate Sunflower Oil entry.
- **WhatsApp Alert:** Now fetches low stock items via AJAX from server endpoint `/inventory-low-stock`. Returns JSON with name, unit, closing, min_stock, log_date.
- **Expense Categories:** Fixed list (raw_materials, masala, salary, packing_materials, cleaning_materials, petty_cash, utilities, maintenance_repairs, marketing, others) + dynamic custom categories stored in `expense_categories` table.

### ✅ FIX: Inventory Item Master Table UI Fixes (12 Mar 2026)
- **Issue:** Current Stock column was showing in Item Master table (should be managed by daily entry only), and column alignment was inconsistent across category tables
- **Fix 1 — Removed Current Stock column:** Deleted "Current Stock" column header and value cells from `resources/views/inventory/index.blade.php`
- **Fix 2 — Fixed Minimum Stock display:** Verified `minimum_stock` column is the correct one (not `minimum_stock_qty`)
- **Fix 3 — Updated create/edit forms:** Removed "Current Stock" input field (already not present), updated Minimum Stock label to "Minimum Stock (triggers Low Stock alert when closing stock falls below this)"
- **Fix 4 — Low stock alert logic:** Updated to compare latest log's closing stock against `minimum_stock`:
  - Updated `InventoryController@index()` to load latest log closing for each item
  - Updated `index.blade.php` to use `$item->logs->first()->closing` instead of `$item->current_stock`
  - Logic: If no log exists → show OK, If closing < minimum_stock → Low Stock (red), If closing >= minimum_stock → OK (green)
- **Fix 5 — Table column alignment (Updated):** Applied fixed widths using `<colgroup>` with Tailwind classes:
  - Item: `w-48` (192px)
  - Unit: `w-20` (80px)
  - Min Stock: `w-32` (128px)
  - Last Updated: `w-36` (144px)
  - Status: `w-32` (128px)
  - Actions: `w-40` (160px)
- **Fix 6 — Column alignment classes:** Applied specific alignment classes to headers and cells:
  - Headers: `text-left`, `text-right`, or `text-center` with padding
  - Cells: Matching alignment with `text-gray-700 font-medium` for Min Stock
- **Fix 7 — Mayo badge inline:** Moved "Mayo" badge to same line as item name (removed whitespace between spans)
- **Fix 8 — Drag-and-drop restored:** Fixed CSS to properly show/hide drag handle in drag mode
- **Fix 9 — Uniform table structure:** Verified all 5 category tables have identical colgroup, header, and cell structure
- **Fix 10 — Drag handle overlay approach:** 
  - Removed separate drag handle column (was causing alignment breaks)
  - Drag handle is now an absolutely positioned overlay on the Item cell
  - Table consistently has 6 columns at all times
  - Drag handle visibility controlled via CSS: `.drag-handle { display: none; }` → `table.drag-mode .drag-handle { display: inline-block; }`
  - Added `pl-4` padding to item name when drag mode is active
  - Column widths remain consistent when toggling drag mode
- **Files Updated:**
  - `resources/views/inventory/index.blade.php` — All UI fixes applied
  - `app/Http/Controllers/InventoryController.php` — Updated `index()` method to load latest log closing

### ✅ FIX: Low Stock Logic Across App (12 Mar 2026)
- **Issue:** Low stock status column in Item Master was redundant and low stock highlighting in Daily Entry was inconsistent
- **Change 1 — Remove Status column from Item Master:**
  - Removed STATUS column header and cells from all category tables in `resources/views/inventory/index.blade.php`
  - Removed corresponding `w-32` col from colgroup
  - Expanded ACTIONS column to `w-48` to fill freed space
- **Change 2 — Low stock highlight in Daily Entry:**
  - **Frontend:** Updated `resources/views/inventory/daily.blade.php`:
    - Added `data-min-stock` and `data-item-name` attributes to each row
    - Added `checkLowStock()` JavaScript function to highlight closing input and add "⚠ Low" badge when closing < minimum_stock
    - Updated `saveRow()` to call `checkLowStock()` after successful save
    - Updated page load logic to apply red styling if `$item->is_low_stock` is true
  - **Backend:** Updated `InventoryController@dailyEntry()`:
    - Added `$item->is_low_stock` property to each item based on latest log's closing vs minimum_stock
    - Only sets to true if minimum_stock > 0 and closing < minimum_stock
- **Change 3 — Fix Dashboard low stock list:**
  - Updated `DashboardController@index()` to use new low stock logic:
    - Queries InventoryItem with latest log loading
    - Filters items where closing < minimum_stock (only if minimum_stock > 0)
    - Passes filtered list to dashboard view
- **Files Updated:**
  - `resources/views/inventory/index.blade.php` — Removed Status column
  - `resources/views/inventory/daily.blade.php` — Added low stock highlighting
  - `app/Http/Controllers/InventoryController.php` — Added `$item->is_low_stock` to dailyEntry
  - `app/Http/Controllers/DashboardController.php` — Updated lowStockItems and lowStockList queries

### ✅ NEW: WhatsApp Purchase Alert Feature (12 Mar 2026) — UPDATED 12 Mar 2026
- **Issue:** Manual tracking of low stock items for purchase ordering
- **Original Solution (12 Mar 2026):** Added "📲 Send Purchase Alert" button to Daily Entry page that scanned DOM for `.low-stock-badge` elements
- **Updated Solution (12 Mar 2026):** Changed to fetch low stock items via AJAX from server endpoint
- **Features:**
  - Button placed in top bar next to "History" and "Item Master" buttons
  - Green styling: `bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg text-sm`
  - Scans all rows for items with `.low-stock-badge` (⚠ Low)
  - Collects item name, closing stock, and minimum stock for each low item
  - Builds formatted WhatsApp message with:
    - Header: "🛒 *Lord Of Wraps — Purchase Required*"
    - Date: Current date from date picker
    - List of low stock items with stock levels
    - Footer: "Please arrange at the earliest. 🙏"
  - Opens WhatsApp Web with message pre-filled via `https://wa.me/?text=...`
  - Shows alert if no low stock items found
- **JavaScript Function:** `sendWhatsAppAlert()` added to daily.blade.php
- **Files Updated:**
  - `resources/views/inventory/daily.blade.php` — Added button and sendWhatsAppAlert() function

### ✅ CHANGE: Default Inventory Route to Daily Entry (12 Mar 2026)
- **Issue:** Inventory sidebar link was going to Item Master instead of Daily Entry
- **Solution:** Updated sidebar link and added redirect route
- **Changes:**
  - **Sidebar:** Updated `resources/views/layouts/app.blade.php` to link to `route('inventory.daily')` instead of `/inventory`
  - **Routes:** Added redirect route BEFORE resource route in `routes/web.php`:
    ```php
    Route::get('inventory', function() {
        return redirect()->route('inventory.daily');
    })->name('inventory.index');
    ```
  - **Result:** Clicking "Inventory" in sidebar now goes directly to Daily Stock Entry page
  - **Item Master:** Still accessible via "Item Master" button inside the daily entry page
- **Files Updated:**
  - `routes/web.php` — Added redirect route before resource definition
  - `resources/views/layouts/app.blade.php` — Updated sidebar Inventory link

### ✅ CHANGE: Remove Low Stock Highlighting from Daily Entry (12 Mar 2026)
- **Issue:** Low stock highlighting in daily entry was cluttering the UI; wanted to keep WhatsApp alert but remove visual highlights
- **Change 1 — Removed from daily.blade.php:**
  - Removed `checkLowStock()` JavaScript function entirely
  - Removed call to `checkLowStock()` inside `saveRow()`
  - Removed all `.low-stock-badge` elements and warning badges
  - Removed `bg-red-50`, `border-red-400` classes from closing inputs
  - Removed `$rowClass` and `$showWarningIcon` PHP variables
  - Removed `@if($item->is_low_stock)` and related blade conditions
  - Removed `data-min-stock` from row elements (kept `data-item-name` for WhatsApp alert)
- **Files Updated:**
  - `resources/views/inventory/daily.blade.php` — Removed all low stock highlighting logic

### ✅ NEW: WhatsApp Alert Server-Side Fetch (12 Mar 2026)
- **Issue:** WhatsApp alert was reading from DOM badges which were removed
- **Solution:** Updated to fetch low stock items via AJAX from server
- **Backend:**
  - Added `getLowStock()` method to `InventoryController`:
    - Queries InventoryItem where minimum_stock > 0
    - Loads latest log with ordering by log_date desc, limit 1
    - Filters items where closing < minimum_stock
    - Returns JSON with: name, unit, closing, min_stock, log_date
  - Added route: `GET /inventory-low-stock` → `inventory.lowStock`
- **Frontend:**
  - Replaced `sendWhatsAppAlert()` function to:
    - Fetch from `{{ route("inventory.lowStock") }}`
    - Handle empty response: "✅ All items are sufficiently stocked."
    - Format message with proper date (d M Y format)
    - Include unit in stock display
- **Files Updated:**
  - `app/Http/Controllers/InventoryController.php` — Added getLowStock() method
  - `routes/web.php` — Added inventory-low-stock route
  - `resources/views/inventory/daily.blade.php` — Updated sendWhatsAppAlert() function

### ✅ NEW: Expense Categories with Fixed List + Dynamic Custom (12 Mar 2026)
- **Issue:** Hardcoded expense categories, no way for users to add custom categories
- **Solution:** Fixed list of categories + ability to add custom categories stored in database
- **Database:**
  - Created migration `2026_03_12_140549_create_expense_categories_table`:
    - `id`, `label`, `value` (unique), `is_custom` (boolean), `sort_order`, `timestamps`
  - Migration ran successfully
- **Model:** Created `app/Models/ExpenseCategory.php` with `$fillable = ['label', 'value', 'is_custom', 'sort_order']`
- **Controller:** Created `app/Http/Controllers/ExpenseCategoryController.php`:
  - `store()` method: validates name, generates slug from label, checks for duplicates, creates custom category
- **Route:** Added `POST /expense-categories` → `expense-categories.store`
- **ExpenseController:** Updated `create()` and `edit()` methods to pass `$customCategories` to views
- **Views - Create/Edit:**
  - Replaced category dropdown with fixed list + custom categories + "Add New Category..." option
  - Fixed categories: raw_materials, masala, salary, packing_materials, cleaning_materials, petty_cash, utilities, maintenance_repairs, marketing, others
  - Added JavaScript for handling "Add New Category...":
    - Shows hidden input field when selected
    - AJAX POST to create new category
    - Dynamically adds new option to dropdown
  - Files: `resources/views/expenses/create.blade.php`, `resources/views/expenses/edit.blade.php`
- **Views - Index:**
  - Added category label map for display:
    - raw_materials → Raw Materials
    - masala → Masala
    - salary → Salary
    - packing_materials → Packing Materials
    - cleaning_materials → Cleaning Materials
    - petty_cash → Petty Cash
    - utilities → Utilities
    - maintenance_repairs → Maintenance and Repairs
    - marketing → Marketing
    - others → Others
   - Files: `resources/views/expenses/index.blade.php`, `resources/views/accounts/index.blade.php`

---

## Dark Glass Theme Implementation (13 Mar 2026)

### Overview
Updated the app to use a dark glass theme with modern styling. The theme uses CSS classes defined in the layout file (`layouts/app.blade.php`) or inline styles.

### Theme Classes Used
| Class | Description |
|-------|-------------|
| `glass-card` | Dark glass card container with blur effect |
| `glass-card s1` to `s4` | Glass card with numbered variant styling |
| `glass-table` | Table with glass/styled headers and cells |
| `stat-label` | Label text for dashboard stat cards |
| `stat-value` | Large value text for dashboard stat cards |
| `stat-sub` | Subtitle text for dashboard stat cards |
| `card-title` | Title for card sections |
| `page-title` | Main page title |
| `page-subtitle` | Page subtitle |
| `btn-accent` | Primary action button (yellow/gold) |
| `btn-ghost` | Ghost/secondary button |
| `btn-ghost btn-sm` | Small ghost button |
| `btn-sm` | Small button variant |
| `btn-danger btn-sm` | Danger/delete button |
| `stock-input` | Input field for stock entries |
| `alert-success` | Success alert styling |
| `alert-error` | Error alert styling |
| `form-input` | Form input field |
| `form-select` | Form select dropdown |
| `form-textarea` | Form textarea |
| `form-label` | Form label |
| `form-group` | Form field wrapper |
| `form-error` | Form validation error |
| `today-pill` | "TODAY" badge styling |
| `badge badge-low` | Low stock badge |
| `badge-active` | Active status badge |
| `badge-inactive` | Inactive status badge |
| `badge-paid` | Paid status badge |
| `badge-unpaid` | Unpaid status badge |
| `badge-pending` | Pending status badge |
| `badge-received` | Received status badge |
| `badge-disputed` | Disputed status badge |

### Dark Glass Theme - All Pages (13 Mar 2026)

#### Files Updated:
- `resources/views/expenses/create.blade.php`
- `resources/views/expenses/edit.blade.php`
- `resources/views/suppliers/index.blade.php`
- `resources/views/suppliers/create.blade.php`
- `resources/views/suppliers/edit.blade.php`
- `resources/views/staff/index.blade.php`
- `resources/views/staff/create.blade.php`
- `resources/views/staff/edit.blade.php`
- `resources/views/payroll/index.blade.php`
- `resources/views/payroll/create.blade.php`
- `resources/views/payroll/edit.blade.php`
- `resources/views/inventory/index.blade.php`
- `resources/views/inventory/create.blade.php`
- `resources/views/inventory/edit.blade.php`
- `resources/views/inventory/history.blade.php`
- `resources/views/inventory/daily.blade.php` (already done)
- `resources/views/accounts/index.blade.php`
- `resources/views/settlements/index.blade.php`

#### General Changes Applied:
1. **Wrappers:** Removed `bg-white`, `bg-gray-50`, `bg-gray-100`, `min-h-screen`
2. **Cards:** Replaced `bg-white rounded-xl shadow` with `glass-card`
3. **Form Inputs:** Replaced with `form-input` class
4. **Form Selects:** Replaced with `form-select` class
5. **Form Textareas:** Replaced with `form-textarea` class
6. **Form Labels:** Replaced with `form-label` class
7. **Field Wrappers:** Wrapped in `<div class="form-group">`
8. **Primary Buttons:** Replaced with `btn-accent`
9. **Secondary Buttons:** Replaced with `btn-ghost`
10. **Delete/Danger Buttons:** Replaced with `btn-danger btn-sm`
11. **Tables:** Replaced with `glass-table w-full`
12. **Table Headers:** Removed color classes, added sticky styling
13. **Flash Messages:** Replaced `bg-green-100` with `alert-success`, `bg-red-100` with `alert-error`
14. **Stat Cards:** Used `stat-label` and `stat-value` divs

#### Page-Specific Updates:
- **expenses/create, edit:** Max-width 640px centered card
- **suppliers/index:** Added "+ Add Supplier" btn-accent button, status badges
- **suppliers/create, edit:** Max-width 640px centered card
- **staff/index:** Added "+ Add Staff" btn-accent button, status badges
- **staff/create, edit:** Max-width 640px centered card
- **payroll/index:** Added "+ Record Payroll" btn-accent button, status badges
- **payroll/create, edit:** Max-width 640px centered card
- **inventory/index:** Added "+ Add Item" btn-accent button, drag handle styling
- **inventory/create, edit:** Max-width 640px centered card
- **inventory/history:** Table with sticky header and max-height scroll
- **inventory/daily:** Already done - kept AJAX/Alpine.js intact
- **accounts/index:** Tab logic intact, glass-card stat summaries, glass-table tables
- **settlements/index:** Badge classes for status, Generate button, Mark Received/Edit buttons

### ✅ FIX: Settlement Duplicate Prevention & Cleanup (13 Mar 2026)
- **Issue:** Generate Settlements was creating duplicate settlement records
- **Solution:**
  1. Added `cleanupDuplicateSettlements()` method in `SettlementController` constructor
  2. Runs on every controller instantiation - keeps earliest record for each platform+period combination, deletes rest
  3. Changed from manual check + create to `firstOrCreate()` for atomic duplicate prevention
- **Files Updated:**
  - `app/Http/Controllers/SettlementController.php`

### ✅ FIX: Settlement Edit Modal Auto-Open Bug (13 Mar 2026)
- **Issue:** Edit Settlement modal was opening automatically on page load
- **Cause:** Inline `style="display:flex"` had higher CSS specificity than `.hidden` class
- **Solution:**
  1. Changed modal from `class="hidden" style="display:flex..."` to `style="display:none; ..."` (hidden by default)
  2. Updated JavaScript to use inline `style.display = 'flex'` to show and `style.display = 'none'` to hide
  3. Added click-outside-to-close functionality
- **Files Updated:**
  - `resources/views/settlements/index.blade.php`

### ✅ NEW: Backup & Restore Feature (13 Mar 2026)
- **Database:** Added `role` column to `users` table (enum: admin, staff, default: staff)
- **Middleware:** Created `AdminMiddleware` and `AuthMiddleware` (auth not enforced - no login system)
- **Controller:** Created `BackupController` with:
  - `index()` - Shows backup/restore page with last backup info
  - `backup()` - Generates SQL dump via mysqldump or PHP fallback, downloads as flavordesk_backup_YYYY-MM-DD_HH-mm.sql
  - `restore()` - Accepts .sql file upload, validates (required, file, mimes:sql,txt, max 50MB), executes via DB::unprepared()
- **Routes:** Added in web.php (no auth middleware - open access)
  - `GET /backup` → backup.index
  - `POST /backup/download` → backup.download
  - `POST /backup/restore` → backup.restore
- **Sidebar:** Added "Backup" link after Settlements with 💾 icon
- **View:** Created `resources/views/backup/index.blade.php`:
  - Two cards: Backup Database (left) and Restore Database (right)
  - Green "Download Backup" button
  - Red "Restore Database" button with warning text
  - JavaScript confirmation dialog before restore
- **Features:**
  - Backup uses mysqldump if available, falls back to pure PHP (loops tables, gets CREATE TABLE + row data)
  - Restore splits SQL on semicolons carefully (handles multi-line statements, strings)
  - Shows success/error flash messages
- **Files Created:**
  - `app/Http/Controllers/BackupController.php`
  - `app/Http/Middleware/AdminMiddleware.php`
  - `app/Http/Middleware/AuthMiddleware.php`
  - `database/migrations/2026_03_13_122345_add_role_to_users_table.php`
  - `resources/views/backup/index.blade.php`
- **Files Updated:**
  - `routes/web.php` - Added backup routes
  - `resources/views/layouts/app.blade.php` - Added Backup sidebar link

### ✅ FIX: Accounts Modal Auto-Open Bug (13 Mar 2026)
- **Issue:** Mark Settlement as Received modal was opening automatically on page load
- **Cause:** Same as settlements bug - inline `style="display:flex"` overriding `.hidden` class
- **Solution:**
  1. Removed `display:flex` from inline style (was: `style="position:fixed; display:flex; ..."`, now: `style="position:fixed; ..."`)
  2. Modal uses `class="hidden"` to hide by default
  3. JavaScript toggles `classList.remove('hidden')` and `classList.add('hidden')`
  4. Added backdrop click handler to close modal
   5. Added Escape key handler to close modal
- **Files Updated:**
  - `resources/views/accounts/index.blade.php`

### ✅ FIX: Item Master Button (13 Mar 2026)
- **Issue:** "Item Master" button on Daily Stock Entry page was redirecting to dashboard due to route override
- **Solution:**
  1. Added new route: `GET /inventory-master` → `inventory.master` → `InventoryController@index`
  2. Updated button in `daily.blade.php` to use `route('inventory.master')` instead of `route('inventory.index')`
- **Files Updated:**
  - `routes/web.php` - Added inventory-master route
  - `resources/views/inventory/daily.blade.php` - Updated button link

### ✅ NEW: InventoryItemSeeder (13 Mar 2026)
- **Issue:** Inventory items table was empty after database truncate
- **Solution:** Created `InventoryItemSeeder` with 23 Lord of Wraps inventory items:
  - Shawarma Marination (5): Shawarma Chicken, LB, PP, MED TT, Zinger
  - Mayo, Masala & Sauces (11): Eggless Mayo, LB Sauce, MX Sauce, Burger Spicy/Sweet, Peri Peri Sauce/Masala, B/T Powder, Zinger Masala/Powder
  - Chicken & Fish (3): Chilled Chicken, Frozen Chicken, Fish
  - Bun, Bakery & Grocery (2): Rumali, Khubz
  - Other (2): Sunflower Oil, Palm Oil
- **Files Created:**
  - `database/seeders/InventoryItemSeeder.php`
- **Usage:** `php artisan db:seed --class=InventoryItemSeeder`

### ✅ FIX: Daily Inventory Save Error (13 Mar 2026)
- **Issue:** "Error" text appeared on Save button when saving second time for same item/date
- **Cause:** JavaScript error handling didn't properly check response status
- **Solution:**
  1. Improved error handling to check explicit 2xx status codes
  2. Added proper 422 validation error handling with alert messages
  3. Button now resets to "Save" on any error instead of showing "Error"
- **Files Updated:**
  - `resources/views/inventory/daily.blade.php`

### ✅ NEW: Bulk Sales Entry (13 Mar 2026)
- **Issue:** Only one sale entry per form submission
- **Solution:** Redesigned Record Sale flow to record all payment channels in one shot
- **Backend:**
  - Added `bulkStore()` method to `SaleController`
  - Uses `updateOrCreate` to prevent duplicates (matches on date + type + platform)
  - Auto-calculates 31% commission for Swiggy/Zomato
  - Skips empty/zero amounts
- **Route:** `POST /sales-bulk` → `sales.bulkStore`
- **Frontend:**
  - Modal with date picker (defaults to today)
  - Standard channels: Cash, Google Pay, Swiggy Gross, Zomato Gross (all optional)
  - "Other / Credit Sale" section with dynamic add/remove rows
  - Live running TOTAL at bottom
  - Single "Save All Sales" button
- **Database:** Added 'other' to sale_type ENUM via `ALTER TABLE`
- **Files Updated:**
  - `app/Http/Controllers/SaleController.php` - Added bulkStore method
  - `routes/web.php` - Added sales-bulk route
  - `resources/views/sales/index.blade.php` - New bulk entry modal

### ✅ FIX: Sales Table Column Alignment (13 Mar 2026)
- **Issue:** Data cells not aligned under headers
- **Solution:**
  - Added `!important` inline styles to override CSS `.glass-table th { text-align: left }`
  - Headers: Date, Type, Platform → left | Gross, Commission, Net → right | Settlement, Actions → center
  - Data cells: Matching alignment with consistent padding (12px 16px)
- **Files Updated:**
  - `resources/views/sales/index.blade.php`

### ✅ NEW: Sales Page - Collapsible Cards + 3-Column Grid Layout (14 Mar 2026)
- **Changes:**
  1. **Collapsible Sections:** TODAY'S SALES and THIS MONTH now collapse/expand on click using Alpine.js
  2. **Header with Total:** Each section header shows label + total sum + chevron icon
  3. **3-Column Grid:** Cards displayed in 3-column CSS grid (`grid grid-cols-3 gap-4`)
  4. **Card Layout:**
     - TODAY'S SALES Row 1: CASH | GOOGLE PAY | ONLINE (GROSS)
     - TODAY'S SALES Row 2: ACTUAL IN HAND | CREDIT SALES | (empty)
     - THIS MONTH Row 1: CASH | GOOGLE PAY | ONLINE (GROSS)
     - THIS MONTH Row 2: ACTUAL RECEIVED | ONLINE PENDING | ONLINE CREDITED
  5. **New Cards Added:**
     - CREDIT SALES: Shows today's credit sales (sale_type = 'other', gross_amount)
     - ONLINE PENDING: Sum of net_amount for swiggy/zomato with settlement_status = 'pending' this month
     - ONLINE CREDITED: Sum of net_amount for swiggy/zomato with settlement_status = 'received' this month
- **Styling:**
  - CREDIT SALES: Purple border (#a78bfa)
  - ONLINE PENDING: Amber border (#f59e0b)
  - ONLINE CREDITED: Green border (#10b981)
- **Backend:**
  - Added `$creditSalesToday` in SaleController - sums gross_amount for today where sale_type = 'other'
  - Added `$onlinePendingMonth` - sums net_amount for this month where sale_type IN ('swiggy','zomato') AND settlement_status = 'pending'
  - Added `$onlineCreditedMonth` - sums net_amount for this month where sale_type IN ('swiggy','zomato') AND settlement_status = 'received'
- **Files Updated:**
  - `app/Http/Controllers/SaleController.php` - Added new calculations
  - `resources/views/sales/index.blade.php` - Collapsible sections with grid layout
  - `resources/views/layouts/app.blade.php` - Added Alpine.js collapse plugin

---

## Recent Fixes (15 Mar 2026)

### ✅ FIX: Item Master 500 Error (15 Mar 2026)
- **Problem:** `$items->isEmpty()` called on grouped Collection causing 500 error
- **Fix:** Changed to `$items->flatten()->isNotEmpty()` in `resources/views/inventory/index.blade.php`

### ✅ FIX: Carry-Forward Logic (15 Mar 2026)
- **Problem:** Day 1 closing was being copied to Day 2 closing (should only go to opening)
- **Fix:** In `InventoryController.php`, carry-forward now:
  - Creates next day log with ONLY: opening, purchased, consumption, wastage, opening_source
  - Does NOT include `closing` in create array - MySQL generated column auto-calculates
  - When updating existing next day log, only updates `opening` and `opening_source`
- **Files Updated:**
  - `app/Http/Controllers/InventoryController.php`

### ✅ FIX: Backup Restore Failing Due to Generated Columns (15 Mar 2026)
- **Problem:** Restore fails because backup includes values for generated columns (total, closing)
- **Fix:** 
  - Added `SET SESSION sql_mode=''` at top of mysqldump backup file
  - PHP backup method already skips generated columns
- **Files Updated:**
  - `app/Http/Controllers/BackupController.php`

### ✅ FIX: Credit Sales Double-Counted in Record Sales Total (15 Mar 2026)
- **Problem:** Credit sale amount was being added twice to the total
- **Fix:** 
  - Added `.credit-amount` class to credit entry amount inputs
  - Used event delegation to handle dynamically added credit entries
  - Simplified selector to use class names
- **Files Updated:**
  - `resources/views/sales/index.blade.php`

### ✅ FIX: Credit Entries Validation Error (15 Mar 2026)
- **Problem:** Validation error when saving credit entries with "required_with" rule
- **Fix:** Changed validation rules from `required_with` to `nullable` for credit_entries
- **Files Updated:**
  - `app/Http/Controllers/SaleController.php`

### ✅ FIX: Closing Display as Zero Until Activity (15 Mar 2026)
- **Problem:** Day 2 closing shows same value as opening even before activity
- **Fix:** In `daily.blade.php`, closing now displays 0 if both consumption=0 AND wastage=0
- **Files Updated:**
  - `resources/views/inventory/daily.blade.php`

### ✅ ADD: WhatsApp Screenshot Share Button (15 Mar 2026)
- **Feature:** Added Share button in navbar to capture and share screenshots via WhatsApp
- **Functionality:**
  - Captures full-page screenshot using html2canvas
  - Replaces inputs with styled divs to capture values in screenshot
  - Downloads as `flavordesk-[page]-[date].png`
  - Opens WhatsApp Web in new tab
- **Files Updated:**
  - `resources/views/layouts/app.blade.php`

