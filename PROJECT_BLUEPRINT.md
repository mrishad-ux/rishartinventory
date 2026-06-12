# PROJECT_BLUEPRINT.md - FlavorDesk

**Last Updated:** 15 Mar 2026  
**Purpose:** Complete blueprint for recreating the FlavorDesk application from scratch

---

## 1. PROJECT OVERVIEW

### Application Details
- **App Name:** FlavorDesk
- **Business:** Lord of Wraps - QSR (Quick Service Restaurant) / Shawarma & Wrap outlet
- **Stack:** Laravel 12, MySQL, Tailwind CSS (CDN), Alpine.js, Blade templates
- **PHP Version:** 8.2+
- **Deployment:** Railway (web + MySQL), GitHub for version control
- **Local Dev:** XAMPP (MySQL), Windows 11, PHP 8.2

### Project Structure
```
C:\Users\mrish\Downloads\RestaurantManager\FlavorDesk
```

---

## 2. DATABASE SCHEMA

### Tables & Columns

#### users
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| name | varchar(255) | | |
| email | varchar(255) | UNIQUE | |
| password | varchar(255) | | |
| role | enum('admin','staff') | | 'staff' |
| email_verified_at | timestamp | nullable | |
| remember_token | varchar(100) | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### suppliers
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| name | varchar(255) | | |
| phone | varchar(20) | nullable | |
| email | varchar(255) | nullable | |
| address | text | nullable | |
| product_supplied | varchar(255) | nullable | |
| outstanding_balance | decimal(10,2) | | 0.00 |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### inventory_items
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| name | varchar(255) | | |
| category | varchar(50) | | |
| unit | varchar(20) | | 'kg' |
| current_stock | decimal(10,2) | | 0 |
| minimum_stock | decimal(10,2) | | 0 |
| minimum_stock_qty | decimal(10,2) | nullable | |
| unit_price | decimal(10,2) | nullable | |
| supplier_id | bigint | FK → suppliers, nullable | |
| is_mayo | boolean | | false |
| sort_order | int | | 0 |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### inventory_logs
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| inventory_item_id | bigint | FK → inventory_items, cascade | |
| log_date | date | | |
| opening | decimal(10,2) | | 0 |
| opening_source | varchar(20) | nullable | 'default' |
| purchased | decimal(10,2) | | 0 |
| total | decimal(10,2) | **GENERATED** (storedAs) | opening + purchased |
| consumption | decimal(10,2) | | 0 |
| wastage | decimal(10,2) | | 0 |
| closing | decimal(10,2) | **GENERATED** (storedAs) | opening + purchased - consumption - wastage |
| mayo_oil_qty | decimal(10,2) | nullable | |
| mayo_milk_qty | decimal(10,2) | nullable | |
| mayo_bottles | decimal(10,2) | nullable | |
| notes | text | nullable | |
| gas_changed | boolean | | false |
| electricity_reading | decimal(10,2) | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |
| **UNIQUE** | | (inventory_item_id, log_date) | |

#### expenses
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| title | varchar(255) | | |
| category | varchar(50) | | |
| amount | decimal(10,2) | | |
| expense_date | date | | |
| payment_type | enum('cash','credit') | | 'cash' |
| status | enum('paid','unpaid') | | 'unpaid' |
| due_date | date | nullable | |
| supplier_id | bigint | FK → suppliers, nullable | |
| notes | text | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### expense_categories
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| label | varchar(50) | | |
| value | varchar(50) | UNIQUE | |
| is_custom | boolean | | false |
| sort_order | int | | 0 |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### sales
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| sale_date | date | | |
| sale_type | enum('cash','gp','swiggy','zomato','other') | | 'cash' |
| platform | varchar(50) | nullable | |
| gross_amount | decimal(10,2) | | |
| commission_percent | decimal(5,2) | | 0 |
| commission_amount | decimal(10,2) | | 0 |
| net_amount | decimal(10,2) | | |
| settlement_status | enum('not_applicable','pending','received') | | 'not_applicable' |
| expected_settlement_date | date | nullable | |
| actual_settlement_date | date | nullable | |
| notes | text | nullable | |
| customer_name | varchar(255) | nullable | |
| customer_phone | varchar(20) | nullable | |
| customer_notes | text | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### staff
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| name | varchar(255) | | |
| role | varchar(100) | | |
| phone | varchar(20) | nullable | |
| salary_type | enum('daily','monthly') | | 'monthly' |
| salary_amount | decimal(10,2) | | |
| joining_date | date | nullable | |
| status | enum('active','inactive') | | 'active' |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### payroll
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| staff_id | bigint | FK → staff, cascade | |
| payment_date | date | | |
| days_worked | int | | 0 |
| basic_amount | decimal(10,2) | | 0 |
| bonus | decimal(10,2) | | 0 |
| deduction | decimal(10,2) | | 0 |
| net_amount | decimal(10,2) | | 0 |
| status | enum('paid','unpaid') | | 'unpaid' |
| notes | text | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |

#### platform_settlements
| Column | Type | Constraints | Default |
|--------|------|-------------|---------|
| id | bigint | PK, auto-increment | |
| platform | enum('swiggy','zomato') | | |
| period_from | date | | |
| period_to | date | | |
| expected_credit_date | date | nullable | |
| actual_credit_date | date | nullable | |
| gross_amount | decimal(10,2) | | 0 |
| estimated_commission | decimal(10,2) | | 0 |
| estimated_net | decimal(10,2) | | 0 |
| actual_amount_received | decimal(10,2) | nullable | |
| actual_commission | decimal(10,2) | nullable | |
| status | enum('pending','received','disputed') | | 'pending' |
| notes | text | nullable | |
| created_at | timestamp | | |
| updated_at | timestamp | | |

### Inventory Item Categories
```php
'shawarma_marination' => 'MARINATION'
'mayo_masala_sauces'  => 'Mayo, Masala & Sauces'
'chicken_fish'        => 'Chicken & Fish'
'bun_bakery'          => 'Bun, Bakery & Grocery'
'other'               => 'Other'
```

---

## 3. ALL MODULES & FEATURES

### Dashboard (`/dashboard`)
- **Route:** `GET /dashboard` → `DashboardController@index`
- **View:** `resources/views/dashboard.blade.php`
- **Features:**
  - Today's sales summary card (total of all sale types)
  - This month sales card with expense total
  - Unpaid expenses card with supplier outstanding
  - Active staff count card
  - Low stock alerts card (items below minimum)
  - Recent sales table (last 5 sales)
  - Low stock items table

### Suppliers (`/suppliers`)
- **Route:** Resource controller `SupplierController`
- **View:** `resources/views/suppliers/index.blade.php`
- **Features:**
  - CRUD operations
  - Outstanding balance tracking
  - Payment status (paid/unpaid expenses)
  - Contact information (phone, email, address)
  - Product supplied field

### Inventory (`/inventory-daily`, `/inventory-master`)
- **Route:** 
  - `GET /inventory-daily` → `InventoryController@dailyEntry`
  - `GET /inventory-master` → `InventoryController@index`
  - `POST /inventory/{id}/log` → `InventoryController@saveLog`
- **Views:** 
  - `resources/views/inventory/daily.blade.php`
  - `resources/views/inventory/index.blade.php`
- **Features:**
  - **Item Master:** List all inventory items by category, CRUD operations, current stock display
  - **Daily Entry:** Grid layout with opening/purchased/wastage/consumption/closing for each item
  - **Carry-Forward Logic:** Yesterday's closing → today's opening (auto-created next day log)
  - **Gas Cylinder Tracking:** "Did you change gas?" prompt with Yes/No buttons
  - **Electricity Meter:** Previous reading display, new reading input
  - **Color Coding:** Green border on saved rows, blue background for auto-filled opening
  - **Purchase Alert:** Button to send low stock alert (requires WhatsApp integration)
  - **Closing Display:** Shows 0 until consumption OR wastage > 0

### Expenses (`/expenses`)
- **Route:** Resource controller `ExpenseController`
- **View:** `resources/views/expenses/index.blade.php`
- **Features:**
  - CRUD operations
  - Categories (Rent, Electricity, Gas, Supplies, Maintenance, Salary Advance, Other)
  - Payment type (cash/credit)
  - Status (paid/unpaid)
  - Due date for credit expenses
  - Filter by status

### Sales (`/sales`)
- **Route:** 
  - Resource controller `SaleController`
  - `POST /sales-bulk` → `SaleController@bulkStore`
- **View:** `resources/views/sales/index.blade.php`
- **Features:**
  - **Bulk Record Form:** Modal with:
    - Cash amount
    - GPay amount
    - Swiggy gross (31% commission auto-calculated)
    - Zomato gross (31% commission auto-calculated)
    - Credit sales (add multiple entries with name, phone, amount, notes)
  - Live total calculation (Cash + GPay + Swiggy + Zomato + sum of credit)
  - Sales table with filters
  - Collapsible sections (Today, This Month, Credit Sales, Online Pending, Online Credited)
  - Settlement status tracking (not_applicable/pending/received)

### Staff (`/staff`)
- **Route:** Resource controller `StaffController`
- **View:** `resources/views/staff/index.blade.php`
- **Features:**
  - CRUD operations
  - Role/position field
  - Salary type (daily/monthly)
  - Salary amount
  - Status (active/inactive)
  - Joining date

### Payroll (`/payroll`)
- **Route:** Resource controller `PayrollController`
- **View:** `resources/views/payroll/index.blade.php`
- **Features:**
  - Process salary for staff
  - Days worked input
  - Basic amount, bonus, deduction fields
  - Net amount calculation
  - Payment status (paid/unpaid)

### Accounts (`/accounts`)
- **Route:** `GET /accounts` → `AccountsController@index`
- **View:** `resources/views/accounts/index.blade.php`
- **Features:**
  - Daily/Weekly/Monthly toggle (Alpine.js)
  - Sales breakdown by type
  - Expense breakdown by category
  - Profit calculation
  - Platform settlements overview
  - Mark settlement received modal

### Settlements (`/settlements`)
- **Route:** 
  - Resource controller `SettlementController` (index, update)
  - `POST /settlements/generate` → `SettlementController@generate`
  - `POST /settlements/{id}/mark-received` → `SettlementController@markReceived`
- **View:** `resources/views/settlements/index.blade.php`
- **Features:**
  - Swiggy section (week: Sunday to Saturday)
  - Zomato section (week: Monday to Sunday)
  - Credit Sales section
  - Generate button to create new settlement periods
  - Duplicate prevention (firstOrCreate with platform + period check)
  - Mark as received functionality

### Backup (`/backup`) - Admin Only
- **Route:** 
  - `GET /backup` → `BackupController@index`
  - `POST /backup/download` → `BackupController@backup`
  - `POST /backup/restore` → `BackupController@restore`
- **View:** `resources/views/backup/index.blade.php`
- **Features:**
  - Download database backup (SQL file)
  - mysqldump method (primary) with PHP fallback
  - Restore from SQL file upload
  - SET SESSION sql_mode='' in backup for generated columns
  - Skips total/closing columns in INSERT statements

---

## 4. KEY TECHNICAL DECISIONS & PATTERNS

### Update or Create Pattern
```php
// Prevents duplicate entries on re-save for daily inventory
$log = InventoryLog::updateOrCreate(
    ['inventory_item_id' => $id, 'log_date' => $date],
    ['opening' => ..., 'purchased' => ..., ...]
);
```

### Schema Safety Checks (Required for Railway)
```php
// Always check if table/column exists before creating
if (!Schema::hasTable('inventory_logs')) {
    Schema::create('inventory_logs', function (Blueprint $table) { ... });
}

// Check column before adding
if (!Schema::hasColumn('inventory_logs', 'gas_changed')) {
    $table->boolean('gas_changed')->default(false);
}
```

### HTTPS Force in Production
```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    if (env('APP_ENV') === 'production') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
```

### Alpine.js Modal Pattern
```html
<!-- Always initialize show/open as false -->
<div x-data="{ open: false }">
    <button @click="open = true">Open</button>
    <div x-show="open" @click.outside="open = false">Content</div>
</div>
```

### Dark Theme Colors (Glassmorphism)
```css
:root {
    --accent: #f59e0b;        /* Amber */
    --bg-primary: #0f0e0d;    /* Near black */
    --bg-secondary: #1a1612; /* Dark brown */
    --card-border: rgba(251,146,60,0.12);
}
```

### Sale Type Values
```php
'cash', 'gp', 'swiggy', 'zomato', 'other'
```

### Credit Sales Storage
- Stored in `sales` table with `sale_type = 'other'`
- Customer fields: `customer_name`, `customer_phone`, `customer_notes`
- `platform` field stores customer name for 'other' type

### Opening Source Values
```php
'default'   // Initial value (0)
'auto'      // Carry-forward from previous day closing
'manual'    // Manually edited by staff
```

### Admin Middleware
- Backup/Restore pages protected by role check
- Check: `auth()->user()->role === 'admin'`

### html2canvas Screenshot with Input Values
```javascript
// Replace inputs with divs showing values before capture
var inputs = document.querySelectorAll('input[type="text"], input[type="number"]');
var replacements = [];
inputs.forEach(function(input) {
    if (input.style.display === 'none') return;
    var div = document.createElement('div');
    div.textContent = input.value || input.placeholder || '0';
    // Copy styles from input
    div.style.cssText = window.getComputedStyle(input).cssText;
    div.style.display = 'flex';
    // ... positioning code
    input.parentNode.insertBefore(div, input);
    input.style.display = 'none';
    replacements.push({ input, div });
});
// After capture: restore inputs
```

---

## 5. KNOWN BUGS & FIXES APPLIED

### Modal Auto-Opening Bug
- **Symptom:** Modals open automatically on page load instead of staying closed
- **Cause:** Alpine.js x-data initialized with `show: true` or inline `style="display:flex"`
- **Fix:** Always initialize `show`/`open` as `false` in x-data

### Duplicate Settlements
- **Symptom:** Multiple settlement records created for same platform + period
- **Fix:** Use `firstOrCreate()` with unique key constraint on (platform, period_from, period_to)

### Generated Column Restore Error
- **Symptom:** Restore fails because backup includes values for generated columns
- **Fix:** Skip 'total' and 'closing' columns in INSERT statements, add `SET SESSION sql_mode=''`

### Double-Counted Credit Sales
- **Symptom:** Credit sale amount added twice in total calculation
- **Cause:** Two event listeners (inline oninput + DOMContentLoaded addEventListener)
- **Fix:** Use single class `.credit-amount` with event delegation

### Carry-Forward Closing Field Issue
- **Symptom:** Day 2 closing shows same as opening before activity
- **Fix:** Only display closing value when `consumption > 0 OR wastage > 0`

### Item Master 500 Error
- **Symptom:** $items->isEmpty() called on grouped Collection
- **Fix:** Use `$items->flatten()->isNotEmpty()`

### Closing Display as Zero
- **Problem:** Closing shows auto-calculated value even when no activity
- **Fix:** Blade condition `($consumption > 0 || $wastage > 0) ? $closingRaw : 0`

---

## 6. DEPLOYMENT CHECKLIST

### Required Files

#### nixpacks.toml
```toml
[build]
php = "8.2"

[deploy.build]
cmd = "composer install --no-dev --optimize-autoloader"

[deploy.start]
cmd = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
```

#### Procfile (if not using nixpacks)
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

### Railway Environment Variables
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate with php artisan key:generate>
DB_HOST=<railway mysql host>
DB_PORT=3306
DB_DATABASE=flavordesk
DB_USERNAME=<railway mysql username>
DB_PASSWORD=<railway mysql password>
```

### Migration Safety Requirements
1. Always wrap Schema::create in `if (!Schema::hasTable(...))`
2. Always wrap column additions in `if (!Schema::hasColumn(...))`
3. Use try-catch for schema operations in up() method

### Start Command (Railway)
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### After Stable Deployment
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 7. FILE STRUCTURE

### Controllers (`app/Http/Controllers/`)
| File | Description |
|------|-------------|
| DashboardController.php | Dashboard stats and overview |
| InventoryController.php | Item master, daily entry, logs, carry-forward |
| SaleController.php | Sales CRUD, bulk record |
| SupplierController.php | Suppliers CRUD |
| ExpenseController.php | Expenses CRUD |
| ExpenseCategoryController.php | AJAX category creation |
| StaffController.php | Staff CRUD |
| PayrollController.php | Payroll CRUD |
| AccountsController.php | Accounts dashboard |
| SettlementController.php | Platform settlements |
| BackupController.php | Backup/restore |

### Models (`app/Models/`)
| File | Description |
|------|-------------|
| User.php | User model |
| Supplier.php | Supplier model |
| InventoryItem.php | Inventory items |
| InventoryLog.php | Daily stock logs |
| Expense.php | Expense records |
| ExpenseCategory.php | Expense categories |
| Sale.php | Sales records |
| Staff.php | Staff members |
| Payroll.php | Payroll records |
| PlatformSettlement.php | Platform settlements |

### Migrations (`database/migrations/`)
| File | Description |
|------|-------------|
| 0001_01_01_000000_create_users_table.php | Users table |
| 0001_01_01_000001_create_cache_table.php | Cache table |
| 0001_01_01_000002_create_jobs_table.php | Jobs table |
| 2025_01_01_000011_create_inventory_logs.php | Inventory logs with generated columns |
| 2026_03_09_061933_create_suppliers_table.php | Suppliers |
| 2026_03_09_062018_create_inventory_items_table.php | Inventory items |
| 2026_03_09_062033_create_expenses_table.php | Expenses |
| 2026_03_09_062038_create_sales_table.php | Sales |
| 2026_03_09_062042_create_staff_table.php | Staff |
| 2026_03_09_062048_create_payroll_table.php | Payroll |
| 2026_03_11_180042_create_platform_settlements_table.php | Platform settlements |
| 2026_03_12_140549_create_expense_categories_table.php | Expense categories |
| 2026_03_10_161536_change_closing_to_regular_column_in_inventory_logs.php | Closing column fix |
| 2026_03_10_164519_add_sort_order_to_inventory_items.php | Sort order |
| 2026_03_11_012017_add_opening_source_to_inventory_logs.php | Opening source |
| 2026_03_11_175017_update_sales_type_swiggy_zomato.php | Sale types |
| 2026_03_11_195004_add_gas_changed_to_inventory_logs.php | Gas tracking |
| 2026_03_12_061153_add_electricity_to_inventory_logs.php | Electricity tracking |
| 2026_03_12_092545_add_unique_constraint_to_inventory_logs.php | Unique constraint |
| 2026_03_13_122345_add_role_to_users_table.php | Admin role |

### Blade Views (`resources/views/`)
| File | Description |
|------|-------------|
| layouts/app.blade.php | Main layout with sidebar, theme widget, WhatsApp share |
| dashboard.blade.php | Dashboard overview |
| welcome.blade.php | Welcome page |
| inventory/index.blade.php | Item master list |
| inventory/create.blade.php | Create item |
| inventory/edit.blade.php | Edit item |
| inventory/show.blade.php | Item history |
| inventory/daily.blade.php | Daily stock entry |
| inventory/history.blade.php | Inventory reports |
| inventory/import.blade.php | CSV import |
| sales/index.blade.php | Sales list + bulk form |
| sales/create.blade.php | Create sale |
| sales/edit.blade.php | Edit sale |
| suppliers/index.blade.php | Suppliers list |
| suppliers/create.blade.php | Create supplier |
| suppliers/edit.blade.php | Edit supplier |
| expenses/index.blade.php | Expenses list |
| expenses/create.blade.php | Create expense |
| expenses/edit.blade.php | Edit expense |
| staff/index.blade.php | Staff list |
| staff/create.blade.php | Create staff |
| staff/edit.blade.php | Edit staff |
| payroll/index.blade.php | Payroll list |
| payroll/create.blade.php | Create payroll |
| payroll/edit.blade.php | Edit payroll |
| accounts/index.blade.php | Accounts dashboard |
| settlements/index.blade.php | Platform settlements |
| backup/index.blade.php | Backup/restore |

---

## 8. SIDEBAR NAVIGATION ORDER

| Order | Route | Icon | Label |
|-------|-------|------|-------|
| 1 | /dashboard | 📊 | Dashboard |
| 2 | /suppliers | 🏪 | Suppliers |
| 3 | /inventory-daily | 📦 | Inventory |
| 4 | /expenses | 💸 | Expenses |
| 5 | /sales | 💰 | Sales |
| 6 | /staff | 👥 | Staff |
| 7 | /payroll | 💵 | Payroll |
| 8 | /accounts | 📈 | Accounts |
| 9 | /settlements | 🏦 | Settlements |
| 10 | /backup | 💾 | Backup |

---

## 9. UI COMPONENTS & CONVENTIONS

### Theme Colors
- **Ember (Default):** Amber (#f59e0b) accent
- **Ocean:** Cyan (#22d3ee) accent
- **Spice:** Gold/Red (#fbbf24) accent
- **Purple:** Violet (#a78bfa) accent
- **Emerald:** Green (#34d399) accent

### Card Styles
- Glassmorphism: `background: rgba(255,255,255,0.03); border: 1px solid rgba(251,146,60,0.12); border-radius: 14px;`

### Badge Colors
| Badge | Background | Border | Text |
|-------|------------|--------|------|
| Cash | rgba(59,130,246,0.15) | rgba(59,130,246,0.25) | #60a5fa |
| GPay | rgba(34,197,94,0.15) | rgba(34,197,94,0.25) | #4ade80 |
| Swiggy | rgba(251,146,60,0.15) | rgba(251,146,60,0.25) | #fb923c |
| Zomato | rgba(239,68,68,0.15) | rgba(239,68,68,0.25) | #f87171 |
| OK/Low | rgba(34,197,94,0.15) | rgba(34,197,94,0.3) | #4ade80 |
| Low | rgba(239,68,68,0.15) | rgba(239,68,68,0.3) | #f87171 |
| Pending | rgba(251,191,36,0.15) | rgba(251,191,36,0.3) | #fbbf24 |
| Paid | rgba(34,197,94,0.15) | rgba(34,197,94,0.3) | #4ade80 |
| Unpaid | rgba(239,68,68,0.15) | rgba(239,68,68,0.3) | #f87171 |

### Status Badge Colors
- **Pending:** Yellow/Amber
- **Received:** Green
- **Disputed:** Orange

### Row Color Coding (Daily Inventory)
- **Not Saved:** Default styling
- **Saved (purchased > 0):** Green border `border: 2px solid #22c55e`
- **Opening (auto):** Blue background `bg-blue-100 border-blue-400`
- **Opening (manual):** Amber background `bg-amber-100 border-amber-400`

### Collapsible Section Pattern (Alpine.js)
```html
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-collapse>Content</div>
</div>
```

### Green Border Highlight on Saved
```html
style="border:2px solid #22c55e; background-color:rgba(34,197,94,0.1);"
```

---

## 10. SEEDER DATA

### Inventory Items by Category

#### MARINATION (shawarma_marination) - Unit: pcs
| Name | Minimum Stock |
|------|---------------|
| Shawarma Chicken | 500 |
| Shawarma LB | 300 |
| Shawarma MED TT | 100 |
| Shawarma PP | 100 |
| Zinger | 200 |

#### MAYO, MASALA & SAUCES (mayo_masala_sauces) - Unit: kg
| Name | Minimum Stock |
|------|---------------|
| Eggless Mayo | 10 |
| LB Sauce | 5 |
| MX Sauce | 5 |
| Burger Spicy | 3 |
| Burger Sweet | 3 |
| Peri Peri Sauce | 2 |
| Peri Peri Masala | 2 |
| B Powder | 2 |
| T Powder | 1 |
| Zinger Masala | 3 |
| Zinger Powder | 2 |

#### CHICKEN & FISH (chicken_fish) - Unit: kg
| Name | Minimum Stock |
|------|---------------|
| Chilled Chicken | 50 |
| Frozen Chicken | 20 |
| Fish | 10 |

#### BUN, BAKERY & GROCERY (bun_bakery) - Unit: pcs
| Name | Minimum Stock |
|------|---------------|
| Rumali | 50 |
| Khubz | 20 |

#### OTHER (other) - Unit: L
| Name | Minimum Stock |
|------|---------------|
| Sunflower Oil | 10 |
| Palm Oil | 10 |

### Default Expense Categories
- Rent
- Electricity
- Gas
- Supplies
- Maintenance
- Salary Advance
- Other

### Commission Rates
- Swiggy: 31%
- Zomato: 31%

### Settlement Periods
- **Swiggy:** Sunday to Saturday (week starts Sunday)
- **Zomato:** Monday to Sunday (week starts Monday)
- **Expected Credit:** Wednesday after period ends

---

## END OF PROJECT_BLUEPRINT.md
