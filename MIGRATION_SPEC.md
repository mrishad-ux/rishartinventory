# MIGRATION_SPEC.md

## Lord Of Wraps / RishArt — Laravel Inventory Management System

**Generated:** 2026-06-07  
**Project Path:** `/root/laravel-rishart`  
**PHP Version:** 8.5.4  
**Laravel Version:** 12  
**Database:** MySQL 8.4.9, database `rishart` (user: `rishart`, pw: `rishart123`)

---

# PART 1: EVERY ROUTE AND WHAT IT DOES

## Public Routes (no middleware)

| Method | URI | Route Name | Controller Method | Purpose |
|--------|-----|------------|-------------------|---------|
| GET | `/login` | `login` | `AuthController@showLoginForm` | Show login page |
| POST | `/login` | *(same)* | `AuthController@login` | Authenticate user (email + password) |
| POST | `/logout` | `logout` | `AuthController@logout` | Logout, invalidate session |
| GET | `/` | *(none)* | `AuthController@showLoginForm` | Root redirects to login page |

## Protected Routes (middleware: `auth`)

### Admin-Only Routes (middleware: `role:admin`)

| Method | URI | Route Name | Controller Method | Purpose |
|--------|-----|------------|-------------------|---------|
| GET | `/dashboard` | `dashboard` | `DashboardController@index` | Admin dashboard with stats |
| GET | `/suppliers` | `suppliers.index` | `SupplierController@index` | List all suppliers |
| GET | `/suppliers/create` | `suppliers.create` | `SupplierController@create` | Show add supplier form |
| POST | `/suppliers` | `suppliers.store` | `SupplierController@store` | Create new supplier |
| GET | `/suppliers/{supplier}` | `suppliers.show` | `SupplierController@show` | Show single supplier |
| GET | `/suppliers/{supplier}/edit` | `suppliers.edit` | `SupplierController@edit` | Show edit supplier form |
| PUT/PATCH | `/suppliers/{supplier}` | `suppliers.update` | `SupplierController@update` | Update supplier |
| DELETE | `/suppliers/{supplier}` | `suppliers.destroy` | `SupplierController@destroy` | Delete supplier |
| GET | `/staff` | `staff.index` | `StaffController@index` | List active/inactive staff |
| GET | `/staff/create` | `staff.create` | `StaffController@create` | Show add staff form |
| POST | `/staff` | `staff.store` | `StaffController@store` | Create staff member |
| GET | `/staff/{staff}` | `staff.show` | `StaffController@show` | Show single staff + recent payroll |
| GET | `/staff/{staff}/edit` | `staff.edit` | `StaffController@edit` | Show edit staff form |
| PUT/PATCH | `/staff/{staff}` | `staff.update` | `StaffController@update` | Update staff member |
| DELETE | `/staff/{staff}` | `staff.destroy` | `StaffController@destroy` | Delete staff member |
| GET | `/payroll` | `payroll.index` | `PayrollController@index` | List payroll records |
| GET | `/payroll/create` | `payroll.create` | `PayrollController@create` | Show add payroll form |
| POST | `/payroll` | `payroll.store` | `PayrollController@store` | Create payroll record |
| GET | `/payroll/{payroll}` | `payroll.show` | `PayrollController@show` | Show single payroll |
| GET | `/payroll/{payroll}/edit` | `payroll.edit` | `PayrollController@edit` | Show edit payroll form |
| PUT/PATCH | `/payroll/{payroll}` | `payroll.update` | `PayrollController@update` | Update payroll record |
| DELETE | `/payroll/{payroll}` | `payroll.destroy` | `PayrollController@destroy` | Delete payroll record |
| GET | `/backup` | `backup.index` | `BackupController@index` | Show backup/restore page |
| POST | `/backup/download` | `backup.download` | `BackupController@backup` | Download SQL backup |
| POST | `/backup/restore` | `backup.restore` | `BackupController@restore` | Restore from SQL file |

### Admin + Manager Routes (middleware: `role:admin,manager`)

| Method | URI | Route Name | Controller Method | Purpose |
|--------|-----|------------|-------------------|---------|
| GET | `/inventory` | `inventory.index` | *(redirect)* | Redirects to `inventory.daily` |
| GET | `/inventory-master` | `inventory.master` | `InventoryController@index` | Item Master list by category |
| GET | `/inventory/create` | `inventory.create` (resource) | `InventoryController@create` | Add new item form |
| POST | `/inventory` | `inventory.store` (resource) | `InventoryController@store` | Create new inventory item |
| GET | `/inventory/{inventory}` | `inventory.show` (resource) | `InventoryController@show` | Item detail + log history |
| GET | `/inventory/{inventory}/edit` | `inventory.edit` (resource) | `InventoryController@edit` | Edit item form |
| PUT/PATCH | `/inventory/{inventory}` | `inventory.update` (resource) | `InventoryController@update` | Update inventory item |
| DELETE | `/inventory/{inventory}` | `inventory.destroy` (resource) | `InventoryController@destroy` | Delete inventory item |
| GET | `/inventory-daily` | `inventory.daily` | `InventoryController@dailyEntry` | Daily stock entry page |
| POST | `/inventory/{inventory}/log` | `inventory.saveLog` | `InventoryController@saveLog` | Save single item's daily log |
| GET | `/inventory-history` | `inventory.history` | `InventoryController@history` | View historical stock logs |
| GET | `/inventory-import` | `inventory.import` | `InventoryController@importForm` | CSV import form |
| POST | `/inventory-import` | `inventory.import.store` | `InventoryController@importStore` | Process CSV import |
| POST | `/inventory-reorder` | `inventory.reorder` | `InventoryController@reorder` | Save drag-reordered items |
| POST | `/inventory-gas` | `inventory.saveGas` | `InventoryController@saveGas` | Save gas cylinder change |
| POST | `/inventory-electricity` | `inventory.saveElectricity` | `InventoryController@saveElectricity` | Save electricity meter reading |
| POST | `/inventory-oil` | `inventory.saveOil` | `InventoryController@saveOil` | Save oil consumption |
| GET | `/inventory-oil/monthly-detail` | `inventory.oilMonthlyDetail` | `InventoryController@oilMonthlyDetail` | Get monthly oil breakdown (JSON) |
| GET | `/inventory-oil/monthly-report` | `inventory.oilMonthlyReport` | `ReportController@monthlyOil` | Monthly oil report (JSON) |
| GET | `/inventory-low-stock` | `inventory.lowStock` | `InventoryController@getLowStock` | Get low stock items (JSON) |

### Admin + Accounts Routes (middleware: `role:admin,accounts`)

| Method | URI | Route Name | Controller Method | Purpose |
|--------|-----|------------|-------------------|---------|
| GET | `/expenses` | `expenses.index` | `ExpenseController@index` | List all expenses |
| GET | `/expenses/create` | `expenses.create` | `ExpenseController@create` | Show add expense form |
| POST | `/expenses` | `expenses.store` | `ExpenseController@store` | Create expense |
| GET | `/expenses/{expense}` | `expenses.show` | `ExpenseController@show` | Show single expense |
| GET | `/expenses/{expense}/edit` | `expenses.edit` | `ExpenseController@edit` | Show edit expense form |
| PUT/PATCH | `/expenses/{expense}` | `expenses.update` | `ExpenseController@update` | Update expense |
| DELETE | `/expenses/{expense}` | `expenses.destroy` | `ExpenseController@destroy` | Delete expense |
| GET | `/expenses/{expense}/payment-info` | `expenses.payment-info` | `ExpenseController@paymentInfo` | Get payment info (JSON) |
| POST | `/expense-categories` | `expense-categories.store` | `ExpenseCategoryController@store` | Add custom expense category (JSON) |
| GET | `/payments` | `payments.index` | `PaymentController@index` | List all payments |
| GET | `/payments/create` | `payments.create` | `PaymentController@create` | Show record payment form |
| POST | `/payments` | `payments.store` | `PaymentController@store` | Record a payment |
| GET | `/payments/{payment}` | `payments.show` | `PaymentController@show` | Show single payment |
| DELETE | `/payments/{payment}` | `payments.destroy` | `PaymentController@destroy` | Delete payment (reverse paid_amount) |
| GET | `/sales` | `sales.index` | `SaleController@index` | List all sales |
| GET | `/sales/create` | `sales.create` | `SaleController@create` | Show add sale form |
| POST | `/sales` | `sales.store` | `SaleController@store` | Create single sale |
| GET | `/sales/{sale}` | `sales.show` | `SaleController@show` | Show single sale |
| GET | `/sales/{sale}/edit` | `sales.edit` | `SaleController@edit` | Show edit sale form |
| PUT/PATCH | `/sales/{sale}` | `sales.update` | `SaleController@update` | Update sale |
| DELETE | `/sales/{sale}` | `sales.destroy` | `SaleController@destroy` | Delete sale |
| POST | `/sales-bulk` | `sales.bulkStore` | `SaleController@bulkStore` | Record bulk daily sales (updateOrCreate) |
| GET | `/sales/filter` | `sales.filter` | `SaleController@filter` | Filter sales by date range + types (JSON) |
| GET | `/settlements` | `settlements.index` | `SettlementController@index` | List settlements + credit sales |
| PUT/PATCH | `/settlements/{settlement}` | `settlements.update` | `SettlementController@update` | Update/edit settlement |
| POST | `/settlements/generate` | `settlements.generate` | `SettlementController@generate` | Auto-generate from pending sales |
| POST | `/settlements/{settlement}/mark-received` | `settlements.markReceived` | `SettlementController@markReceived` | Mark settlement as received |
| POST | `/credit-sales/{creditSale}/mark-received` | `creditSales.markReceived` | `SettlementController@markCreditReceived` | Mark credit sale as received |

---

# PART 2: EVERY MODEL AND ITS FIELDS/RELATIONSHIPS

## 2.1 User

**File:** `app/Models/User.php`  
**Table:** `users` (convention)  
**Extends:** `Authenticatable` (Illuminate\Foundation\Auth\User)  
**Traits:** `HasFactory`, `Notifiable`

| Field | Type | Cast | Fillable | Hidden |
|-------|------|------|----------|--------|
| id | BIGINT UNSIGNED PK | — | — | — |
| name | VARCHAR(255) | — | ✅ | — |
| email | VARCHAR(255) UNIQUE | — | ✅ | — |
| role | VARCHAR(50), default 'admin' | — | ✅ | — |
| email_verified_at | TIMESTAMP nullable | `datetime` | — | — |
| password | VARCHAR(255) | `hashed` | ✅ | ✅ |
| remember_token | VARCHAR(100) nullable | — | — | ✅ |
| created_at | TIMESTAMP | — | — | — |
| updated_at | TIMESTAMP | — | — | — |

**Relationships:** None  
**Accessors/Mutators:** None  
**Scopes:** None

---

## 2.2 Expense

**File:** `app/Models/Expense.php`  
**Table:** `expenses` (convention)

| Field | Type | Cast | Fillable | 
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| title | VARCHAR(255) | — | ✅ |
| category | VARCHAR(255) | — | ✅ |
| amount | DECIMAL(10,2) | — | ✅ |
| paid_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| expense_date | DATE | `date` | ✅ |
| supplier_id | BIGINT UNSIGNED, FK→suppliers, nullable | — | ✅ |
| notes | TEXT nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:**
- `supplier()`: `belongsTo(Supplier::class)` — FK: `supplier_id`
- `payments()`: `hasMany(Payment::class)` — FK: `expense_id`

**Accessors:**
- `getPendingAmountAttribute()`: returns `$this->amount - $this->paid_amount`
- `getPaymentStatusAttribute()`: returns `'paid'` if `paid_amount >= amount`; `'partial'` if `paid_amount > 0`; `'pending'` otherwise

**Scopes:**
- `scopePending($query)`: `$query->where('paid_amount', '<', DB::raw('amount'))`
- `scopePartial($query)`: `$query->where('paid_amount', '>', 0)`

---

## 2.3 ExpenseCategory

**File:** `app/Models/ExpenseCategory.php`  
**Table:** `expense_categories` (convention)

| Field | Type | Fillable |
|-------|------|----------|
| id | BIGINT UNSIGNED PK | — |
| label | VARCHAR(255) | ✅ |
| value | VARCHAR(255) UNIQUE | ✅ |
| is_custom | BOOLEAN/TINYINT, default false | ✅ |
| sort_order | INTEGER, default 0 | ✅ |
| created_at | TIMESTAMP | — |
| updated_at | TIMESTAMP | — |

**Relationships:** None  
**Accessors/Mutators:** None  
**Scopes:** None

---

## 2.4 InventoryItem

**File:** `app/Models/InventoryItem.php`  
**Table:** `inventory_items` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| name | VARCHAR(255) | — | ✅ |
| category | VARCHAR(255) nullable | — | ✅ |
| unit | VARCHAR(255), default 'kg' | — | ✅ |
| current_stock | DECIMAL(10,2), default 0.00 | — | ✅ |
| minimum_stock | DECIMAL(10,2), default 0.00 | — | ✅ |
| unit_price | DECIMAL(10,2), default 0.00 | — | ✅ |
| supplier_id | BIGINT UNSIGNED, FK→suppliers, nullable | — | ✅ |
| minimum_stock_qty | DECIMAL(10,2), default 0.00, nullable | — | ✅ |
| is_mayo | BOOLEAN/TINYINT, default false | `boolean` | ✅ |
| sort_order | INTEGER, default 0 | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Static Properties:**
- `$categories = ['shawarma_marination' => 'MARINATION', 'mayo_masala_sauces' => 'Mayo, Masala & Sauces', 'chicken_fish' => 'Chicken & Fish', 'bun_bakery' => 'Bun, Bakery & Grocery', 'other' => 'Other']`

**Relationships:**
- `supplier()`: `belongsTo(Supplier::class)` — FK: `supplier_id`
- `logs()`: `hasMany(InventoryLog::class)` — FK: `inventory_item_id`
- `todayLog()`: `hasOne(InventoryLog::class)->whereDate('log_date', today())` — FK: `inventory_item_id`
- `latestLog()`: `hasOne(InventoryLog::class)->latestOfMany('log_date')` — FK: `inventory_item_id`
- **Note:** `todayLog()` and `latestLog()` are both `hasOne` relationships — you cannot have two `hasOne` on the same FK with different constraints in standard Eloquent without the `ofMany` helper (which `latestLog` uses). This is a potential issue.

**Methods:**
- `isLowStock(): bool`: returns `$this->current_stock <= $this->minimum_stock`

---

## 2.5 InventoryLog

**File:** `app/Models/InventoryLog.php`  
**Table:** `inventory_logs` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| inventory_item_id | BIGINT UNSIGNED, FK→inventory_items, part of UNIQUE | — | ✅ |
| log_date | DATE, part of UNIQUE | `date` | ✅ |
| opening | DECIMAL(10,2), default 0.00 | — | ✅ |
| opening_source | VARCHAR(255), default 'default' | — | ✅ |
| purchased | DECIMAL(10,2), default 0.00 | — | ✅ |
| total | DECIMAL(10,2) | **GENERATED ALWAYS AS (opening + purchased) STORED** | — |
| consumption | DECIMAL(10,2), default 0.00 | — | ✅ |
| wastage | DECIMAL(10,2), default 0.00 | — | ✅ |
| closing | DECIMAL(10,2), default 0.00 | — | ✅ |
| mayo_oil_qty | DECIMAL(10,2) nullable | — | ✅ |
| mayo_milk_qty | DECIMAL(10,2) nullable | — | ✅ |
| mayo_bottles | DECIMAL(10,2) nullable | — | ✅ |
| notes | TEXT nullable | — | ✅ |
| gas_changed | BOOLEAN/TINYINT, default false | — | — |
| electricity_reading | DECIMAL(10,2) nullable | — | — |
| oil_l1_packets | DECIMAL(10,2) nullable | — | ✅ |
| oil_l2_packets | DECIMAL(10,2) nullable | — | ✅ |
| oil_r1_packets | DECIMAL(10,2) nullable | — | ✅ |
| oil_r2_packets | DECIMAL(10,2) nullable | — | ✅ |
| oil_mayo_packets | DECIMAL(10,2) nullable | — | ✅ |
| oil_sauces_packets | DECIMAL(10,2) nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**UNIQUE constraint:** `(inventory_item_id, log_date)` — named `inventory_logs_item_date_unique`

**Relationships:**
- `item()`: `belongsTo(InventoryItem::class)` — FK: `inventory_item_id`

**Accessors:**
- `getClosingAttribute($value)`: Logs debug info. Returns DB stored `$value` if truthy; otherwise calculates `$this->opening + $this->purchased - $this->consumption - $this->wastage` (fallback if DB value is falsy).

**Important:** `gas_changed`, `electricity_reading`, and oil fields are NOT in `$fillable` — they are set directly via `update()` on the model or via raw database queries in the controller.

---

## 2.6 Payment

**File:** `app/Models/Payment.php`  
**Table:** `payments` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| expense_id | BIGINT UNSIGNED, FK→expenses | — | ✅ |
| amount | DECIMAL(10,2) | — | ✅ |
| payment_date | DATE | `date` | ✅ |
| notes | TEXT nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:**
- `expense()`: `belongsTo(Expense::class)` — FK: `expense_id`

---

## 2.7 Payroll

**File:** `app/Models/Payroll.php`  
**Table:** `payroll` (explicit)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| staff_id | BIGINT UNSIGNED, FK→staff ON DELETE CASCADE | — | ✅ |
| payment_date | DATE | `date` | ✅ |
| days_worked | INTEGER, default 0 | — | ✅ |
| basic_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| bonus | DECIMAL(10,2), default 0.00 | — | ✅ |
| deduction | DECIMAL(10,2), default 0.00 | — | ✅ |
| net_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| status | ENUM('paid','unpaid'), default 'unpaid' | — | ✅ |
| notes | TEXT nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:**
- `staff()`: `belongsTo(Staff::class)` — FK: `staff_id`

---

## 2.8 PlatformSettlement

**File:** `app/Models/PlatformSettlement.php`  
**Table:** `platform_settlements` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| platform | ENUM('swiggy','zomato') | — | ✅ |
| period_from | DATE | `date` | ✅ |
| period_to | DATE | `date` | ✅ |
| expected_credit_date | DATE | `date` | ✅ |
| actual_credit_date | DATE nullable | `date` | ✅ |
| gross_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| estimated_commission | DECIMAL(10,2), default 0.00 | — | ✅ |
| estimated_net | DECIMAL(10,2), default 0.00 | — | ✅ |
| actual_amount_received | DECIMAL(10,2) nullable | — | ✅ |
| actual_commission | DECIMAL(10,2) nullable | — | ✅ |
| status | ENUM('pending','received','disputed'), default 'pending' | — | ✅ |
| notes | TEXT nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:** None

**Accessors:**
- `getCommissionPercentAttribute()`: If `status === 'received'` AND `actual_commission > 0` AND `gross_amount > 0` → returns `round(($this->actual_commission / $this->gross_amount) * 100, 2)`. Otherwise returns `31.0` (hardcoded default).

---

## 2.9 Sale

**File:** `app/Models/Sale.php`  
**Table:** `sales` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| sale_date | DATE | `date` | ✅ |
| sale_type | ENUM('cash','gp','online','swiggy','zomato') | — | ✅ |
| platform | VARCHAR(255) nullable | — | ✅ |
| gross_amount | DECIMAL(10,2) | — | ✅ |
| commission_percent | DECIMAL(5,2), default 0.00 | — | ✅ |
| commission_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| net_amount | DECIMAL(10,2) | — | ✅ |
| settlement_status | ENUM('not_applicable','pending','received'), default 'not_applicable' | — | ✅ |
| expected_settlement_date | DATE nullable | `date` | ✅ |
| actual_settlement_date | DATE nullable | `date` | ✅ |
| notes | VARCHAR(255) nullable | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:** None  
**Accessors/Mutators:** None

---

## 2.10 Staff

**File:** `app/Models/Staff.php`  
**Table:** `staff` (convention)

| Field | Type | Cast | Fillable |
|-------|------|------|----------|
| id | BIGINT UNSIGNED PK | — | — |
| name | VARCHAR(255) | — | ✅ |
| role | VARCHAR(255) | — | ✅ |
| phone | VARCHAR(255) nullable | — | ✅ |
| salary_type | ENUM('daily','monthly'), default 'monthly' | — | ✅ |
| salary_amount | DECIMAL(10,2), default 0.00 | — | ✅ |
| joining_date | DATE nullable | `date` | ✅ |
| status | ENUM('active','inactive'), default 'active' | — | ✅ |
| created_at | TIMESTAMP | — | — |
| updated_at | TIMESTAMP | — | — |

**Relationships:**
- `payrolls()`: `hasMany(Payroll::class)` — FK: `staff_id`

---

## 2.11 Supplier

**File:** `app/Models/Supplier.php`  
**Table:** `suppliers` (convention)

| Field | Type | Fillable |
|-------|------|----------|
| id | BIGINT UNSIGNED PK | — |
| name | VARCHAR(255) | ✅ |
| phone | VARCHAR(255) nullable | ✅ |
| email | VARCHAR(255) nullable | ✅ |
| address | TEXT nullable | ✅ |
| product_supplied | VARCHAR(255) nullable | ✅ |
| outstanding_balance | DECIMAL(10,2), default 0.00 | ✅ |
| created_at | TIMESTAMP | — |
| updated_at | TIMESTAMP | — |

**Relationships:** None

---

## Relationship Map (Graph)

```
Expense ──hasMany──> Payment
Expense ──belongsTo──> Supplier
Payment ──belongsTo──> Expense

InventoryItem ──hasMany──> InventoryLog
InventoryItem ──hasOne──> InventoryLog (todayLog: whereDate=now())
InventoryItem ──hasOne──> InventoryLog (latestLog: latestOfMany date)
InventoryItem ──belongsTo──> Supplier
InventoryLog ──belongsTo──> InventoryItem

Staff ──hasMany──> Payroll
Payroll ──belongsTo──> Staff

Sales (no relationships)
PlatformSettlement (no relationships)
ExpenseCategory (no relationships)
User (no relationships)
Supplier (no relationships—referenced by Expense and InventoryItem via belongsTo on those models)
```

---

# PART 3: EVERY FORM AND ITS FIELDS + VALIDATION RULES

## 3.1 Login Form

**View:** `auth/login.blade.php`  
**Action:** POST `/login`  
**CSRF:** ✅

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| email | input[email] | required, autocomplete="email", autofocus | `required\|email` |
| password | input[password] | required, autocomplete="current-password", with eye toggle | `required` |
| — | button[submit] | "Sign In", amber styling | — |

**Client-side JS:** Password visibility toggle (eye icon swap).

---

## 3.2 Sale: Single Create Form

**View:** `sales/create.blade.php`  
**Action:** POST `/sales`  
**CSRF:** ✅  
**Alpine.js:** `x-data="{ saleType: 'cash', isOnline() { ... } }"`

| Field | Type | Alpine Show | Attributes | Validation Rules |
|-------|------|-------------|-----------|-----------------|
| sale_date | input[date] | always | required | `required\|date` |
| sale_type | select | always | options: cash/gp/swiggy/zomato, x-model="saleType" | `required\|in:cash,gp,swiggy,zomato` |
| platform | select | x-show="isOnline()" | options: Swiggy/Zomato/Other | `nullable` |
| gross_amount | input[number] | always | step="0.01" | `required\|numeric` |
| commission_percent | input[number] | x-show="isOnline()" | default 31 | `nullable\|numeric` |
| expected_settlement_date | input[date] | x-show="isOnline()" | — | `nullable` |
| notes | textarea | always | — | `nullable` |

---

## 3.3 Sale: Edit Form

**View:** `sales/edit.blade.php`  
**Action:** POST `/sales/{sale}`, method=PUT  
**CSRF:** ✅  
**Alpine.js:** `x-data="{ saleType: '$sale->sale_type', isOnline() { ... }, isCredit() { ... } }"`

| Field | Type | Alpine Show | Value Source | Validation Rules |
|-------|------|-------------|-------------|-----------------|
| sale_date | input[date] | always | `$sale->sale_date` | `required\|date` |
| sale_type | select | always | `$sale->sale_type`, options: cash/gp/swiggy/zomato/other | `required\|in:cash,gp,swiggy,zomato,other` |
| platform | select | isOnline() | `$sale->platform` | `nullable` |
| customer_name | input[text] | isCredit() | `$sale->customer_name` | `nullable` |
| customer_phone | input[text] | isCredit() | `$sale->customer_phone` | `nullable` |
| gross_amount | input[number] | always | `$sale->gross_amount` | `required\|numeric` |
| commission_percent | input[number] | isOnline() | `$sale->commission_percent` | `nullable\|numeric` |
| settlement_status | select | isOnline() or isCredit() | options: pending/received | `nullable` |
| expected_settlement_date | input[date] | isOnline() | `$sale->expected_settlement_date` | `nullable` |
| actual_settlement_date | input[date] | isOnline() | `$sale->actual_settlement_date` | `nullable` |
| customer_notes | textarea | isCredit() | `$sale->customer_notes` | `nullable` |
| notes | textarea | always | `$sale->notes` | `nullable` |

---

## 3.4 Sale: Bulk Create Form

**View:** `sales/index.blade.php` (bulk modal)  
**Action:** POST `/sales-bulk`  
**CSRF:** ✅

| Field | Type | Attributes | Dynamic | Validation Rules |
|-------|------|-----------|---------|-----------------|
| sale_date | input[date] | value=today | No | `required\|date` |
| cash_amount | input[number] | step="0.01", min="0", data-type="cash" | No | `nullable\|numeric\|min:0` |
| gp_amount | input[number] | step="0.01", min="0", data-type="gp" | No | `nullable\|numeric\|min:0` |
| swiggy_gross | input[number] | step="0.01", min="0", data-type="swiggy" | No | `nullable\|numeric\|min:0` |
| zomato_gross | input[number] | step="0.01", min="0", data-type="zomato" | No | `nullable\|numeric\|min:0` |
| credit_entries[N][name] | input[text] | required | Yes (Add Entry btn) | `nullable\|string` |
| credit_entries[N][phone] | input[text] | — | Yes | not validated server-side |
| credit_entries[N][amount] | input[number] | required | Yes | `nullable\|numeric\|min:0` |
| credit_entries[N][notes] | input[text] | — | Yes | not validated server-side |

---

## 3.5 Sale: Filter Form (AJAX)

**View:** `sales/index.blade.php`  
**Action:** GET `/sales/filter` (AJAX)  
**No CSRF** (GET request)

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| filter-date-from | input[date] | default: start of month | `required\|date` |
| filter-date-to | input[date] | default: today | `required\|date\|after_or_equal:date_from` |
| types[] | checkbox[] x4 | cash/gp/swiggy/zomato, all checked | `required\|array`, `types.*\|in:cash,gp,swiggy,zomato` |

---

## 3.6 Expense: Create Form

**View:** `expenses/create.blade.php`  
**Action:** POST `/expenses`  
**CSRF:** ✅

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| title | input[text] | required | `required` |
| category | select | onchange triggers custom category logic | `required` |
| #new-category-name (hidden) | input[text] | appears when "__add_new__" selected | (AJAX) `required\|string\|max:100` |
| amount | input[number] | step="0.01", required | `required\|numeric` |
| expense_date | input[date] | default today | `required\|date` |
| supplier_id | select | options from `$suppliers` | `nullable\|exists:suppliers,id` |
| notes | textarea | — | `nullable` |

**Custom Categories defined in ExpenseCategory model:** raw_materials, masala, salary, packing_materials, cleaning_materials, petty_cash, utilities, maintenance_repairs, marketing, others + dynamic custom.

**JS:** `handleCategoryChange(select)` — shows/hides custom category input; `saveNewCategory()` — AJAX POST to `/expense-categories`; `cancelNewCategory()`.

---

## 3.7 Expense: Edit Form

**View:** `expenses/edit.blade.php`  
**Action:** POST `/expenses/{expense}`, method=PUT  
Same fields as create, pre-populated from `$expense`. Same JS.

---

## 3.8 Payment: Create Form

**View:** `payments/create.blade.php`  
**Action:** POST `/payments`  
**CSRF:** ✅

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| expense_search | input[text] | client-side filter only | — |
| expense_id | select | options have data-amount/data-paid/data-pending; loaded from expenses with pending balance | `required\|exists:expenses,id` |
| amount | input[number] | step="0.01", min="0.01", required | `required\|numeric\|min:0.01` |
| payment_date | input[date] | default today | `required\|date` |
| notes | textarea | — | `nullable\|string` |

**JS:** `filterExpenses(query)` — client-side option filtering; `updatePendingAmount(expenseId)` — shows pending from data-attributes, falls back to AJAX GET `/expenses/{id}/payment-info`.

---

## 3.9 Inventory Item: Create Form

**View:** `inventory/create.blade.php`  
**Action:** POST `/inventory`  
**CSRF:** ✅  
**Alpine.js:** `x-data="{ isMayo: false }"`

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| name | input[text] | required | `required\|string\|max:255` |
| category | select | options from $categories enum | `required\|string` |
| unit | select | options: Gms/kg/pkt/Nos/ltr/ml | `required\|string\|max:50` |
| minimum_stock | input[number] | step="0.01", default 0 | `required\|numeric\|min:0` |
| unit_price | input[number] | step="0.01", default 0 | `nullable\|numeric\|min:0` |
| is_mayo | checkbox | value="1", x-model="isMayo" | `nullable` (boolean) |

---

## 3.10 Inventory Item: Edit Form

**View:** `inventory/edit.blade.php`  
Same fields as create, pre-populated from `$inventory`.

---

## 3.11 Inventory: Daily Stock Form

**View:** `inventory/daily.blade.php`  
**Action (per-item):** POST `/inventory/{inventory}/log` (AJAX)  
**CSRF:** ✅

| Field | Type | Per-Item | Attributes | Validation Rules |
|-------|------|---------|-----------|-----------------|
| log_date | input[hidden] | ✅ | value from URL/date picker | `required\|date` |
| opening | input[number] | ✅ | step="0.01", readonly unless toggled, color-coded by source | `required\|numeric\|min:0` |
| opening_source | input[hidden] | ✅ | values: 'default'/'auto'/'manual' | — |
| purchased | input[number] | ✅ | step="0.01" | `required\|numeric\|min:0` |
| wastage | input[number] | ✅ | step="0.01" | `required\|numeric\|min:0` |
| closing | input[number] | ✅ | step="0.01" | `required\|numeric\|min:0` |
| consumption | input[hidden] | ✅ | auto-calculated from opening+purchased-wastage-closing | `required\|numeric\|min:0` |
| mayo_oil_qty | input[hidden] | ✅ (is_mayo only) | step="0.5", from mayo modal | — |
| mayo_milk_qty | input[hidden] | ✅ (is_mayo only) | step="0.5" | — |
| mayo_bottles | input[hidden] | ✅ (is_mayo only) | step="0.5" | — |

---

## 3.12 Inventory: Gas Cylinder Form

**View:** `inventory/daily.blade.php`  
**Action:** POST `/inventory-gas` (AJAX)

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| date | (from state) | — | — (no server-side validation on saveGas) |
| gas_changed | nullable boolean | Yes/No buttons | — |

---

## 3.13 Inventory: Electricity Meter Form

**View:** `inventory/daily.blade.php`  
**Action:** POST `/inventory-electricity` (AJAX)

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| date | input (from state) | — | `required\|date` (in controller) |
| electricity_reading | input[number] | step="any" | `required\|numeric` (in controller) |

---

## 3.14 Inventory: Oil Consumption Form

**View:** `inventory/daily.blade.php`  
**Action:** POST `/inventory-oil` (AJAX)

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| date | (from state) | — | `required\|date` |
| oil_l1_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |
| oil_l2_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |
| oil_r1_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |
| oil_r2_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |
| oil_mayo_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |
| oil_sauces_packets | input[number] | step="0.5", min="0" | `nullable\|numeric\|min:0` |

---

## 3.15 Inventory: CSV Import Form

**View:** `inventory/import.blade.php`  
**Action:** POST `/inventory-import`, `enctype="multipart/form-data"`  
**CSRF:** ✅

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| file | input[file] | accept=".csv,.xlsx,.xls,.txt", hidden behind styled div | `required\|file\|mimes:csv,txt\|max:2048` |

---

## 3.16 Supplier: Create/Edit Forms

**View:** `suppliers/create.blade.php`, `suppliers/edit.blade.php`  
**Action:** POST `/suppliers` or POST `/suppliers/{supplier}`, method=PUT

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| name | input[text] | required | `required` |
| phone | input[text] | — | `nullable` |
| email | input[email] | — | `nullable\|email` |
| product_supplied | input[text] | — | `nullable` |
| address | textarea | — | `nullable` |
| outstanding_balance | input[number] | step="0.01", default 0 | `nullable\|numeric` |

---

## 3.17 Staff: Create/Edit Forms

**View:** `staff/create.blade.php`, `staff/edit.blade.php`  
**Action:** POST `/staff` or POST `/staff/{staff}`, method=PUT

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| name | input[text] | required | `required` |
| role | input[text] | required | `required` |
| phone | input[text] | — | `nullable` |
| salary_type | select | options: daily/monthly | `required\|in:daily,monthly` |
| salary_amount | input[number] | step="0.01", required | `required\|numeric` |
| joining_date | input[date] | default today | `required\|date` |
| status | select | options: active/inactive | `required\|in:active,inactive` |

---

## 3.18 Payroll: Create/Edit Forms

**View:** `payroll/create.blade.php`, `payroll/edit.blade.php`  
**Action:** POST `/payroll` or POST `/payroll/{payroll}`, method=PUT

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| staff_id | select | from active staff | `required\|exists:staff,id` |
| payment_date | input[date] | default today | `required\|date` |
| days_worked | input[number] | step="0.5" | `nullable\|numeric` |
| basic_amount | input[number] | step="0.01", required | `required\|numeric` |
| bonus | input[number] | step="0.01", default 0 | `nullable\|numeric` |
| deduction | input[number] | step="0.01", default 0 | `nullable\|numeric` |
| status | select | options: paid/unpaid | `required\|in:paid,unpaid` |
| notes | textarea | — | `nullable` |

---

## 3.19 Settlement: Mark Received Form

**View:** `settlements/index.blade.php` (modal)  
**Action:** POST `/settlements/{id}/mark-received`

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| actual_amount_received | input[number] | required | `required\|numeric\|min:0` |
| actual_credit_date | input[date] | default today, required | `required\|date` |
| notes | textarea | — | `nullable\|string` |

---

## 3.20 Settlement: Edit Form

**View:** `settlements/index.blade.php` (modal)  
**Action:** POST `/settlements/{id}`, method=PUT

| Field | Type | Attributes | Validation Rules |
|-------|------|-----------|-----------------|
| actual_amount_received | input[number] | — | `nullable\|numeric` |
| actual_credit_date | input[date] | — | `nullable\|date` |
| notes | textarea | placeholder="Deduction breakdown" | `nullable\|string` |
| status | select | options: pending/received/disputed | `required\|in:pending,received,disputed` |

---

## 3.21 Credit Sale: Mark Received Form

**View:** `settlements/index.blade.php`  
**Action:** POST `/credit-sales/{creditSale}/mark-received`

| Field | Type | Attributes |
|-------|------|-----------|
| received_date | input[hidden] | default today |

---

# PART 4: EVERY BUSINESS LOGIC FUNCTION

## 4.1 AuthController

### `login(Request $request)`
1. Look up `User::where('email', $request->email)->first()`
2. If no user OR password doesn't match via `Hash::check()` → redirect back with error 'Invalid email or password'
3. Otherwise: `Auth::login($user)`, `$request->session()->regenerate()`
4. Call `redirectBasedOnRole($user)`:
   - `role === 'admin'` → `redirect()->route('dashboard')` with 'Welcome back, Admin!'
   - `role === 'manager'` → `redirect()->route('inventory.daily')` with 'Welcome back, Manager!'
   - `role === 'accounts'` → `redirect()->route('sales.index')` with 'Welcome back, Accounts!'
   - default → `redirect()->route('dashboard')` with 'Welcome back!'

### `logout(Request $request)`
1. `Auth::logout()`
2. `$request->session()->invalidate()`
3. `$request->session()->regenerateToken()`
4. Redirect to login with success message.

---

## 4.2 DashboardController

### `index()`
**Calculations:**
1. `$todaySales` = SUM of `net_amount` for today's sales where type=cash,gp
2. `$monthSales` = SUM of `net_amount` for this month's sales where type=cash,gp
3. `$unpaidExpenses` = SUM of `(amount - paid_amount)` for all pending expenses
4. `$pendingExpenses` = All pending expenses with supplier, ordered by date DESC
5. `$lowStockItems` = COUNT of inventory items where `minimum_stock > 0` AND latest log's `closing < minimum_stock`
6. `$activeStaff` = COUNT of staff where `status = 'active'`
7. `$todayExpenses` = SUM of `amount` for today's expenses
8. `$monthExpenses` = SUM of `amount` for this month's expenses
9. `$recentSales` = Last 5 sales ordered by sale_date DESC, created_at DESC
10. `$lowStockList` = Collection of items with `minimum_stock > 0` AND log's `closing < minimum_stock` (detail list for modal)

---

## 4.3 SaleController

### `store(Request $request)`
**Conditional logic:**
- IF `sale_type` is `swiggy` or `zomato`:
  - `commission_amount = (gross_amount × commission_percent) / 100`
  - `net_amount = gross_amount - commission_amount`
  - `settlement_status = 'pending'`
- ELSE (cash/gp):
  - `commission_percent = 0`, `commission_amount = 0`
  - `net_amount = gross_amount` (same as gross)
  - `settlement_status = 'not_applicable'`

### `bulkStore(Request $request)`
**Hardcoded:** Swiggy/Zomato commission = **31%**

**Logic (4 independent conditional blocks + 1 loop):**
1. IF `cash_amount > 0` → `updateOrCreate` sale with sale_type='cash' for the given date. net = gross. settlement_status = 'not_applicable'. **Uses updateOrCreate — if a cash entry already exists for that date, it UPDATES it rather than creating a duplicate.**
2. IF `gp_amount > 0` → Same pattern with sale_type='gp'
3. IF `swiggy_gross > 0` → commission = 31%, net = gross - commission, settlement_status = 'pending', platform = 'Swiggy'. updateOrCreate with sale_type='swiggy'.
4. IF `zomato_gross > 0` → Same as swiggy with sale_type='zomato', platform='Zomato'
5. Loop through `credit_entries[]`: IF `name` and `amount` both non-empty → updateOrCreate with sale_type='other', platform=name, settlement_status='pending'. Include customer_name, customer_phone, customer_notes.

**Edge:** If `$savedCount === 0` → redirect with error 'enter at least one sale amount'.

### `update(Request $request, Sale $sale)`
**3-way conditional:**
1. IF `sale_type` is `swiggy` or `zomato` → commission calc, net = gross - commission, clear customer fields
2. ELSEIF `sale_type === 'other'` → no commission, net = gross, settlement_status from request, set customer fields
3. ELSE (cash/gp) → no commission, net = gross, settlement_status = 'not_applicable', clear customer fields

### `filter(Request $request)`
1. Query: `Sale::whereBetween('sale_date', [$from, $to])->whereIn('sale_type', $types)->orderBy('sale_date')->orderBy('sale_type')->get()`
2. Per type: sum `gross_amount`, sum `net_amount`, count
3. Build date-wise breakdown
4. Grand total = sum of all gross_amounts
5. Return JSON

---

## 4.4 ExpenseController

### `index()`
- `$totalPaid` = SUM of `paid_amount` across all expenses
- `$totalUnpaid` = COALESCE(SUM(amount - paid_amount), 0) via raw SQL

### `paymentInfo(Expense $expense)`
- Returns JSON: `{ id, amount, paid_amount, pending_amount }`
- `pending_amount = (float)($expense->amount - $expense->paid_amount)`

---

## 4.5 PaymentController

### `store(Request $request)`
**Transactional (DB::transaction):**
1. `$expense = Expense::findOrFail($validated['expense_id'])`
2. `Payment::create($validated)` — INSERT payment
3. `$newPaidAmount = $expense->paid_amount + $validated['amount']` — add new payment to running total
4. `$cappedAmount = min($newPaidAmount, $expense->amount)` — cap at expense total (never exceed)
5. `$expense->update(['paid_amount' => $cappedAmount])` — UPDATE expense paid_amount

### `destroy(Payment $payment)`
**Transactional (DB::transaction):**
1. `$expense = $payment->expense` — load related expense
2. `$newPaidAmount = $expense->paid_amount - $payment->amount` — subtract removed payment
3. `$expense->update(['paid_amount' => max(0, $newPaidAmount)])` — UPDATE (floor at 0)
4. `$payment->delete()`

---

## 4.6 InventoryController

### `index()` (Item Master)
- Orders by custom category order using `FIELD()` raw SQL: shawarma_marination, mayo_masala_sauces, chicken_fish, bun_bakery, other
- Then by sort_order, then by name
- Groups result by category

### `store(Request $request)`
- Creates inventory item with `current_stock = 0` (always starts at zero)
- `unit_price = $request->unit_price ?? 0`
- `is_mayo = $request->boolean('is_mayo')`

### `reorder(Request $request)`
- Loops `$request->items[]` array, updates each item's `sort_order = index + 1`
- Returns JSON: `{ success: true }`

### `saveGas(Request $request)`
1. Update ALL inventory_logs for the given date: set `gas_changed` to request value
2. Also save to session: `session(["gas_changed_{$date}" => $gasChanged])`
3. Return JSON: `{ success: true, gas_changed: ... }`

### `saveElectricity(Request $request)`
1. Update ALL inventory_logs for the given date: set `electricity_reading`
2. Fetch previous non-null reading from earlier log
3. `$units = $prev ? round($request->electricity_reading - $prev, 2) : null`
4. Also save to session
5. Return JSON: `{ success, units_consumed, prev_reading }`

### `saveOil(Request $request)`
1. Calculate `$fryerTotal = L1 + L2 + R1 + R2`
2. Calculate `$condimentTotal = mayo + sauces`
3. **Stock check:** If fryerTotal > 0, fetch InventoryItem id=30 (palm oil), warn if current_stock < fryerTotal
4. **Stock check:** If condimentTotal > 0, fetch InventoryItem id=29 (sunflower oil), warn if current_stock < condimentTotal
5. Clear existing oil data for this date (set all 6 oil fields to null on all logs for this date)
6. Update first log for this date with new oil values
7. Return JSON with oil values + warnings array

### `oilMonthlyDetail(Request $request)`
1. Parse month start/end
2. Query: `InventoryLog::whereBetween('log_date', [$monthStart, $monthEnd])->whereNotNull(any oil field)->selectRaw('log_date, MAX(COALESCE(oil_l1_packets,0)) as l1, ...')->groupBy('log_date')->orderBy('log_date')->get()`
3. Per row: `total = l1 + l2 + r1 + r2`, `sauces_total = mayo + sauces`
4. `grandTotal = sum of all row totals`
5. Return JSON with month, days array, grand totals

### `getLowStock()`
1. Fetch all items with `minimum_stock > 0`, eager load latest 1 log
2. Filter: `$item->logs->first() && $item->logs->first()->closing < $item->minimum_stock`
3. Map to: `{ name, unit, closing, min_stock, log_date }`
4. Return JSON array

### `dailyEntry(Request $request)` — THE LARGEST METHOD
**Input:** `$date = $request->get('date', today())`

**Per-item logic:**
1. Load all items with their log for this date, ordered by category → sort_order → name
2. For each item:
   - Get `$todayLog = $item->logs->first()` (if any for this date)
   - Get `$prevLog` = log with most recent date before current date
   - If no todayLog: auto-opening = prevLog's closing (or 0), opening_edited = false, is_low_stock = false
   - If todayLog exists: opening_edited = (prevLog exists AND todayLog->opening != prevLog->closing)
   - is_low_stock = (minimum_stock > 0 AND todayLog->closing < minimum_stock)
   - **Important:** For items without a todayLog, `$item->auto_opening` is set (NOT `opening`, to avoid conflicting with model attribute)

**Gas logic:**
- Check if any log for this date has `gas_changed` set → `$gasChanged`
- Fallback: check session if DB is null
- Find most recent date before/on current date where `gas_changed = true` → `$lastGasChange`
- `$daysSinceGasChange = $lastGasChange ? Carbon::parse($lastGasChange)->diffInDays(Carbon::parse($date)) : null`

**Electricity logic:**
- Fetch reading from any log for this date
- Fetch previous non-null reading from log before this date
- `$unitsConsumed = ($electricityReading && $prevElectricityReading) ? round($electricityReading - $prevElectricityReading, 2) : null`

**Oil logic:**
- Fetch oil fields from first log for this date that has oil data
- Fallback: check session for each of 6 fields
- Monthly total: query all days in current month with oil data, sum(L1+L2+R1+R2) per day, then sum all

### `saveLog(Request $request, InventoryItem $inventory)` — SECOND LARGEST

**Step 1: Build data array**
- Collect opening, purchased, wastage, closing, consumption, notes, mayo fields from request
- `opening_source`: If request says 'manual', keep it. Otherwise fetch existing log and preserve its opening_source.
- **Inject session values:** gas_changed from session, electricity_reading from session
- **Mayo fields:** Only if `$inventory->is_mayo`

**Step 2: Save/Update log**
- `InventoryLog::updateOrCreate(['inventory_item_id' => $id, 'log_date' => $logDate], $data)` — uses unique constraint

**Step 3: Stock warning calculation**
- `$consumed = $request->consumption + $request->wastage`
- IF consumed > 0 AND `$inventory->current_stock < $consumed` → warning message generated

**Step 4: Update current_stock**
- `$inventory->update(['current_stock' => max(0, $log->closing)])` — item's current_stock is always set to latest closing

**Step 5: Carry-forward logic**
- Calculate next date = log_date + 1 day
- Check if nextDate log exists:
  - **IF NOT exists**: Create new log for next date with `opening = max(0, today's closing)`, `purchased=0, consumption=0, wastage=0, opening_source='auto'`
  - **IF exists AND nextLog's opening_source is NOT 'manual'**: Update next log's opening to today's closing, set opening_source = 'auto'
  - **IF exists AND nextLog's opening_source IS 'manual'**: Only update opening, do NOT change opening_source (preserve manual edits)

**Step 6: Return response**
- If AJAX → JSON `{ success, closing, total, stockWarning }`
- If stockWarning → redirect with warning flash
- Else → redirect with success flash

### `history(Request $request)`
- Filter by date range (default: last 7 days) and optional category
- Query with `whereHas` for category filter
- Group results by `log_date`

### `importStore(Request $request)`
**CSV processing loop:**
1. Open file, read headers from first row
2. For each row:
   - Skip if empty
   - Combine headers with row values using `array_combine`
   - **4 skip conditions:** missing name, invalid category, missing unit, duplicate name
   - Track `$imported` and `$skipped` counts
   - `is_mayo` = true if category is 'mayo_masala_sauces'
3. Redirect with import stats

---

## 4.7 SettlementController

### `cleanupDuplicateSettlements()`
Called on every request via constructor.
1. Find groups of (platform, period_from, period_to) with COUNT > 1
2. Keep the MIN(id) for each group
3. DELETE all others

### `generate()`
1. Loop through both platforms ('swiggy', 'zomato')
2. Call `generateSettlementsForPlatform($platform, $weekStartDay)`

### `generateSettlementsForPlatform($platform, $weekStartDay)`
1. Query all pending sales for this platform
2. If empty → return
3. Group sales by ISO week (using Carbon: startOfWeek based on platform):
   - **Swiggy:** Week starts Sunday, ends Saturday
   - **Zomato:** Week starts Monday, ends Sunday
4. For each week:
   - Sum gross_amount
   - `estimated_commission = gross × 0.31` (hardcoded 31%)
   - `estimated_net = gross × 0.69` (hardcoded 69%)
   - Calculate expected_credit_date = next Wednesday after period ends
   - `firstOrCreate` settlement record

### `getNextWednesday($date)`
- Advance day-by-day until dayOfWeek === Wednesday
- Return that date

### `markReceived(Request $request, PlatformSettlement $settlement)`
1. `$actualCommission = $settlement->gross_amount - $request->actual_amount_received`
2. Update settlement: actual_amount_received, actual_credit_date, actual_commission, status='received', notes

### `update(Request $request, PlatformSettlement $settlement)`
**Conditional:**
- IF actual_amount_received provided → set it and calculate `actual_commission = gross - actual_amount_received`
- IF actual_credit_date provided → set it
- Always set status and notes

### `markCreditReceived(Request $request, Sale $creditSale)`
1. Validate: `$creditSale->sale_type === 'other'` (reject if not)
2. Update: `settlement_status='received'`, `actual_settlement_date = $request->received_date ?? today()`

---

## 4.8 ExpenseCategoryController

### `store(Request $request)`
1. Trim label
2. Generate slug: `strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $label))`
3. Check duplicate by value
4. If exists → JSON error
5. Create with `is_custom=true, sort_order=99`
6. Return JSON success

---

## 4.9 PayrollController

### `store(Request $request)`
**Calculation:**
- `$data['bonus'] = $request->bonus ?? 0`
- `$data['deduction'] = $request->deduction ?? 0`
- `$data['net_amount'] = $request->basic_amount + $data['bonus'] - $data['deduction']`

### `update(Request $request, Payroll $payroll)`
Same calculation as store.

---

## 4.10 BackupController

### `backup(Request $request)`
**Two backup strategies:**
1. **Preferred (mysqldump):** Find mysqldump binary → build exec command → run → prepend `SET SESSION sql_mode=''` → save to file
2. **Fallback (PHP):** Call `createBackupPhp()` — iterate all tables with SHOW CREATE TABLE and SELECT *, skip generated columns (`total`, `closing` in inventory_logs)

### `createBackupPhp($filePath, $dbName)`
1. SHOW TABLES — get all table names
2. For each table: SHOW CREATE TABLE (for the DDL), SELECT * (for all data)
3. For `inventory_logs` table: skip `total` and `closing` columns in INSERT (they're generated/computed)
4. Build complete SQL including INSERT statements
5. Write to file

### `restore(Request $request)`
1. Validate uploaded .sql file
2. Check not empty
3. `SET FOREIGN_KEY_CHECKS=0`
4. Split SQL into individual statements using `splitSqlStatements()` (character-by-character parser handling quotes)
5. Execute each statement via `DB::unprepared()`
6. `SET FOREIGN_KEY_CHECKS=1`

---

## 4.11 Model Accessors (business logic embedded in models)

### Expense → `getPendingAmountAttribute()`
`amount - paid_amount`

### Expense → `getPaymentStatusAttribute()`
- `paid_amount >= amount` → `'paid'`
- `paid_amount > 0` → `'partial'`
- else → `'pending'`

### InventoryLog → `getClosingAttribute($value)`
- If `$value` is truthy → return `$value` as-is (DB stored value)
- If falsy → calculate: `$this->opening + $this->purchased - $this->consumption - $this->wastage`
- (Also logs debug info)

### PlatformSettlement → `getCommissionPercentAttribute()`
- If `status === 'received'` AND `actual_commission > 0` AND `gross_amount > 0` → `round(($actual_commission / $gross_amount) * 100, 2)`
- Else → `31.0`

### InventoryItem → `isLowStock()`
- Returns `$this->current_stock <= $this->minimum_stock`

---

# PART 5: EVERY PAGE AND WHAT DATA IT SHOWS

## 5.1 Login Page (`auth/login.blade.php`)

**Route:** GET `/login`
**Data passed:** None
**Displays:**
- Brand logo (Rishart_Logo.png, 80px) + "Lord Of Wraps" heading image
- Email input (pre-filled with old value on error)
- Password input with eye toggle
- "Sign In" button
- Error message: `$errors->any()` → first error in styled alert box
- **No layout/app shell** — standalone page with dark gradient background

---

## 5.2 Dashboard (`dashboard.blade.php`)

**Route:** GET `/dashboard` (admin only)
**Data passed:** `todaySales`, `monthSales`, `unpaidExpenses`, `pendingExpenses`, `lowStockItems`, `activeStaff`, `todayExpenses`, `monthExpenses`, `recentSales`, `lowStockList`

**Visible sections:**
- **Title:** "Dashboard" + subtitle "Lord Of Wraps — Operations Overview"
- **Stat cards (4):**
  - Today's Sales: `$todaySales` ₹ (sub: Today's Expenses ₹`$todayExpenses`)
  - This Month Sales: `$monthSales` ₹ (sub: Expenses ₹`$monthExpenses`)
  - Unpaid Expenses: `$unpaidExpenses` ₹ (sub: `$pendingExpenses->count()` bills pending)
  - Active Staff: count (sub: "Currently working")
  - Low Stock Alerts: `$lowStockCount` (sub: "Items need restocking")
- **Recent Sales table:**
  - Type label (Cash/GPay/Swiggy/Zomato), Date, Net Amount
  - Empty: "No sales recorded yet"
- **Pending Expenses modal (click on stat card):**
  - Table: Title, Supplier, Date, Amount, Paid, Due
  - Empty: "All bills are paid!"
- **Low Stock modal (click on stat card):**
  - Table: Item, Category, Stock, Min, Short
  - Empty: "All stock levels are healthy"
- **Buttons:** "View all sales →" → `/sales`, "View inventory →" → `/inventory`

---

## 5.3 Sales Index (`sales/index.blade.php`)

**Route:** GET `/sales` (admin/accounts)
**Data passed:** `sales`, `todayCash`, `todayGP`, `todayOnline`, `todayActual`, `creditSalesToday`, `monthCash`, `monthGP`, `monthOnline`, `monthActual`, `pendingSettlement`, `onlinePendingMonth`, `onlineCreditedMonth`

**Visible sections:**
- **Title:** "Sales" + subtitle "Track and manage sales"
- **Collapsible Today's Summary:**
  - Today's Total (sum of all types)
  - Cash, GPay, Online Gross, Actual In Hand, Credit Sales stat cards
- **Collapsible This Month Summary:**
  - Cash, GPay, Online Gross, Actual In Hand stat cards
  - Online Pending, Online Credited cards
  - Alert if pending settlements > 0
- **All Sales table:**
  - Columns: Date, Type (badge), Platform, Gross, Commission%, Net, Settlement Status, Actions (View/Edit/Delete)
- **Filter section:**
  - Date range (from/to), type checkboxes (cash/gp/swiggy/zomato)
  - "Search" button → AJAX populates filter results below
- **Bulk Sale modal:** 4 channel inputs + dynamic credit entries + auto-total
- **Filter detail modal:** date-wise breakdown from filter results

---

## 5.4 Sales Create (`sales/create.blade.php`)

**Route:** GET `/sales/create` (admin/accounts)
**Data passed:** None
**Form with conditional fields:**
- Sale type selector (cash/gp/swiggy/zomato) — changes which fields show
- Online types show: platform, commission% (default 31), expected settlement date
- Offline types hide platform/commission fields

---

## 5.5 Sales Edit (`sales/edit.blade.php`)

**Route:** GET `/sales/{sale}/edit` (admin/accounts)
**Data passed:** `$sale`
Same conditional form as create, pre-populated. Adds "other" (credit) type support with customer name/phone/notes fields.

---

## 5.6 Sales Show (`sales/show.blade.php`)

**Route:** GET `/sales/{sale}` (admin/accounts)
**Data passed:** `$sale`
**Displays:** All sale fields in labeled cards. Customer info for credit sales. Settlement status timeline.

---

## 5.7 Expenses Index (`expenses/index.blade.php`)

**Route:** GET `/expenses` (admin/accounts)
**Data passed:** `expenses`, `totalPaid`, `totalUnpaid`

**Visible sections:**
- **Title:** "Expenses" + subtitle
- **Stat cards:** Total Paid (`$totalPaid`), Total Unpaid (`$totalUnpaid`)
- **Expenses table:**
  - Columns: Title, Category, Date, Supplier, Amount, Payment Status (paid/partial/pending with pending amount), Actions (View/Edit/Delete)
- **Button:** "+ Add Expense" → create form

---

## 5.8 Expenses Create (`expenses/create.blade.php`)

**Route:** GET `/expenses/create` (admin/accounts)
**Data passed:** `suppliers`, `customCategories`
Form with dynamic category (fixed list + custom with AJAX creation), supplier selector.

---

## 5.9 Expenses Edit (`expenses/edit.blade.php`)

**Route:** GET `/expenses/{expense}/edit`
Same form + data as create, pre-populated.

---

## 5.10 Expenses Show (`expenses/show.blade.php`)

**Route:** GET `/expenses/{expense}`
Displays all expense fields.

---

## 5.11 Payments Index (`payments/index.blade.php`)

**Route:** GET `/payments` (admin/accounts)
**Data passed:** `payments` (paginated 20), `totalPaymentsCount`, `totalAmount`

**Visible sections:**
- **Title:** "Payments"
- **Stat cards:** Total Payments (count), Total Amount Paid (₹)
- **Payments table:** Date, Vendor, Description, Amount, Notes, Actions (View/Delete)
- **Pagination:** `$payments->links()`
- **Button:** "+ Record Payment"

---

## 5.12 Payments Create (`payments/create.blade.php`)

**Route:** GET `/payments/create` (admin/accounts)
**Data passed:** `expenses` (only those with pending balance), `defaultDate`
Form with client-side expense search, pending amount display, dynamic update on expense selection.

---

## 5.13 Payments Show (`payments/show.blade.php`)

**Route:** GET `/payments/{payment}`
Displays payment + related expense info.

---

## 5.14 Inventory Item Master (`inventory/index.blade.php`)

**Route:** GET `/inventory-master` (admin/manager)
**Data passed:** `items` (grouped by category), `categories` (static enum)

**Visible sections:**
- **Per-category tables** (5 categories: MARINATION, Mayo/Masala/Sauces, Chicken/Fish, Bun/Bakery, Other)
  - Each table: Item name (with "Mayo" badge if is_mayo), Unit, Min Stock, Last Updated, Actions (History/Edit/Delete)
  - SortableJS drag-and-drop reorder (togglable)
- **Top buttons:** Daily Entry, Import CSV, Reorder Items, + Add Item
- **Session:** Success flash, import errors display

---

## 5.15 Inventory Create (`inventory/create.blade.php`)

**Route:** GET `/inventory/create` (admin/manager)
**Data passed:** `categories`
Form with Mayo checkbox toggle.

---

## 5.16 Inventory Edit (`inventory/edit.blade.php`)

**Route:** GET `/inventory/{inventory}/edit`
Same form as create, pre-populated.

---

## 5.17 Inventory Show (`inventory/show.blade.php`)

**Route:** GET `/inventory/{inventory}` (admin/manager)
**Data passed:** `inventory`, `logs` (paginated 30)

**Displays:**
- Item details with current stock (red if low)
- Log history table: Date, Opening, Purchased, Total, Consumed, Wastage, Closing (red if ≤0), Oil/Milk/Btl (if mayo)
- "Today" pill on current date logs

---

## 5.18 Inventory Daily Entry (`inventory/daily.blade.php`)

**Route:** GET `/inventory-daily` (admin/manager)  
**Data passed:** `items` (grouped by category), `categories`, `date`, `gasChanged`, `lastGasChange`, `daysSinceGasChange`, `electricityReading`, `prevElectricityReading`, `prevElectricityDate`, `unitsConsumed`, `oilL1`, `oilL2`, `oilR1`, `oilR2`, `oilMayo`, `oilSauces`, `monthlyTotal`

**This is the LARGEST and most complex view (1057 lines). Visible sections:**

**Top bar:**
- Date picker (auto-submits on change)
- "History" button, "Item Master" button, "Send Purchase Alert" button
- Success/warning flash messages

**Gas Cylinder Card:**
- Shows last change date, days since (color: green < 3, red ≥ 4 with warning)
- Yes/No buttons for "changed today?" — AJAX save

**Electricity Meter Card (Alpine.js):**
- Previous reading + date display
- Input for today's reading + Save button (AJAX)
- Units consumed display auto-calculated
- "✓ saved" indicator

**Oil Consumption Card:**
- 4 fryer inputs: L1, L2, R1, R2 (packets)
- 2 condiment inputs: Mayo, Sauces (packets)
- Monthly total (clickable → oil detail modal)
- Palm Oil stock reference, Sunflower stock reference
- Save button (locks on save) / Edit button to unlock

**Category accordion sections (all 5 categories):**
- Collapsible headers with chevron
- Per-item grid rows (12 columns per row):
  - Item name (with "✓ saved" indicator)
  - Unit
  - Opening (color: amber if manually edited, blue if auto-pushed from previous day)
  - Purchased (green highlight when saved)
  - Total (auto-calculated: opening + purchased)
  - Wastage
  - Closing (green highlight when saved)
  - Consumed (auto-calculated: opening + purchased - wastage - closing; red if negative, green otherwise)
  - Save button (shows ✓ Saved / ... saving / Save)

**Mayo Modal:**
- 3 inputs (oil_qty, milk_qty, bottles) — only for mayo items
- Save/Cancel

**Oil Detail Modal:**
- Daily breakdown table of all oil entries for current month
- Fryer totals per day + condiment totals per day + grand totals

---

## 5.19 Inventory History (`inventory/history.blade.php`)

**Route:** GET `/inventory-history` (admin/manager)
**Data passed:** `logs` (grouped by date), `from`, `to`, `category`, `categories`

**Filter form:** Date range (from/to) + category dropdown
**Display:** Logs grouped by date heading → table per date with item details

---

## 5.20 Inventory Import (`inventory/import.blade.php`)

**Route:** GET `/inventory-import` (admin/manager)
**Data passed:** None
Instructions + template download + upload form with styled file input.

---

## 5.21 Suppliers Index (`suppliers/index.blade.php`)

**Route:** GET `/suppliers` (admin only)
**Data passed:** `suppliers`
**Table:** Name, Phone, Product Supplied, Outstanding Balance (red if >0), Actions (View/Edit/Delete)

---

## 5.22 Suppliers Create/Edit/Show

Standard CRUD forms with name, phone, email, product_supplied, address, outstanding_balance. Show page displays all fields with balance color-coded.

---

## 5.23 Staff Index (`staff/index.blade.php`)

**Route:** GET `/staff` (admin only)
**Data passed:** `activeStaff`, `inactiveStaff`

**Two tables:**
1. Active Staff: Name, Role, Phone, Salary Type (Daily/Monthly badge), Salary Amount, Joining Date, Actions
2. Inactive Staff: Name, Role, Phone, Salary Amount, Actions (at 0.6 opacity)

---

## 5.24 Staff Create/Edit/Show

Forms with name, role, phone, salary_type (daily/monthly), salary_amount, joining_date, status (active/inactive). Show has payroll history table.

---

## 5.25 Payroll Index (`payroll/index.blade.php`)

**Route:** GET `/payroll` (admin only)
**Data passed:** `payrolls`, `totalUnpaid`, `monthTotal`

**Stat cards:** This Month's Payroll, Total Unpaid Salaries
**Table:** Staff Name, Payment Date, Days Worked, Basic Amount, Bonus (+₹), Deduction (-₹), Net Pay (bold), Status (Paid/Unpaid badge), Actions

---

## 5.26 Payroll Create/Edit/Show

Forms with staff selector, payment_date, days_worked, basic_amount, bonus, deduction, status, notes. Net = basic + bonus - deduction (auto-calculated server-side). Show displays breakdown.

---

## 5.27 Settlements Index (`settlements/index.blade.php`)

**Route:** GET `/settlements` (admin/accounts)
**Data passed:** `swiggySettlements`, `zomatoSettlements`, `creditSales`

**Three sections:**
1. **Swiggy Settlements (paginated):** Period, Gross, Est Commission (-₹), Est Net (green), Expected Date, Status (pending/received/disputed badge), Variance (if received), Actions (Mark Received / Edit)
2. **Zomato Settlements (paginated):** Same structure
3. **Credit Sales:** Name, Phone, Date, Amount, Notes, Status, Actions (Mark Received)

**Two modals:** Mark Received (amount + date + notes), Edit Settlement (amount + date + notes + status)

**Top button:** "Generate Settlements" → auto-generates from pending platform sales

---

## 5.28 Backup Page (`backup/index.blade.php`)

**Route:** GET `/backup` (admin only)
**Data passed:** `$lastBackup`

**Two cards:**
1. **Download Backup:** Shows last backup timestamp (or "No backup taken yet"), Download button
2. **Restore:** Warning about data overwrite, file upload + Restore button with JS confirm

---

## 5.29 Layout (`layouts/app.blade.php`)

**Role-based sidebar navigation:**
- **All roles:** Dashboard (admin), Suppliers (admin), **Inventory** (admin/manager), Expenses (admin/accounts), Payments (admin/accounts), Sales (admin/accounts), Staff (admin), Payroll (admin), Settlements (admin/accounts), Backup (admin)
- **User section:** User name + role at bottom, Logout (POST form)
- **Footer:** "Lord Of Wraps v1.0"

**Fixed widgets:**
- **Theme selector** (bottom-right FAB): 5 themes (ember/ocean/spice/purple/emerald), density (normal/compact), persisted to localStorage
- **WhatsApp Share** (top-right): Screenshots current page via html2canvas → opens WhatsApp web

**Session flashes:** Success (green) and Error (red) alert bars at top

---

## 5.30 Welcome Page (`welcome.blade.php`)

Default Laravel welcome page for unauthenticated root access. Links to login, docs, Laracasts, Forge, Vapor, GitHub. Only shown if not logged in.

---

# APPENDIX A: Hardcoded Values

| Value | Where Used | Purpose |
|-------|-----------|---------|
| `31` (percentage) | `SaleController@bulkStore`, `SettlementController@generateSettlementsForPlatform` | Swiggy/Zomato commission rate |
| `0.31` / `0.69` | `SettlementController@generateSettlementsForPlatform` | Commission/net split ratios |
| `30` (item ID) | `InventoryController@saveOil` | Palm Oil inventory item ID for stock warning |
| `29` (item ID) | `InventoryController@saveOil` | Sunflower Oil inventory item ID for stock warning |
| `31.0` (percentage) | `PlatformSettlement@getCommissionPercentAttribute` | Default commission percent when not yet received |
| `99` (sort_order) | `ExpenseCategoryController@store` | Default sort order for custom categories |
| `'default'` (string) | `InventoryLog` table, `InventoryController` | Default opening_source value |
| `'auto'` (string) | `InventoryController@saveLog` | opening_source when carry-forward pushes opening |
| `'manual'` (string) | `InventoryController@saveLog` | opening_source when staff manually edits |

# APPENDIX B: Edge Cases & State Transitions

1. **Inventory daily entry:** If no log exists for a date, the page shows `auto_opening = previous day's closing` (or 0). But this value is NOT saved until the user clicks "Save" on that row. The opening shown in the input is a computed property, not a persisted value.

2. **Bulk sales:** Uses `updateOrCreate` keyed on `(sale_date, sale_type)`. This means running bulk sales TWICE on the same date will UPDATE the previous entry rather than creating a duplicate. Intended for corrections.

3. **Payments:** Creating a payment increments `expense.paid_amount` (capped at expense.amount). Deleting a payment decrements it (floored at 0). These run inside DB transactions.

4. **Settlement generation:** Uses `firstOrCreate` keyed on `(platform, period_from, period_to)`. Running generation twice produces no duplicates. The `cleanupDuplicateSettlements()` runs on every request to clean up pre-existing dups.

5. **Inventory carry-forward:** Only overwrites the next day's opening if `opening_source !== 'manual'`. Staff's manual edits are preserved.

6. **Electricity reading:** Follows the *first* log for a date, not a specific item. Multiple logs on the same date share the same reading (set via mass update).

7. **Gas changed:** Set via session *and* DB. If you save gas for a date but no inventory log has been created yet, the session preserves the value until `saveLog` injects it into the new log.

8. **Oil consumption:** Clears ALL oil data for a date before saving, then writes to the first log for that date. This means saving oil twice overwrites completely.

9. **Stock warning on oil save:** Checks Palm Oil item (ID 30) for fryer total, Sunflower Oil (ID 29) for condiment total. These are hardcoded IDs — WILL break if item IDs change.

10. **Expenses:** No longer have `payment_type`, `status`, or `due_date` columns (dropped in a migration). Payment tracking is now via the separate `payments` table + `paid_amount` column on `expenses`.