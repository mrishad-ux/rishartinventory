<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [AuthController::class, 'showLoginForm']);

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('suppliers', SupplierController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('payroll', PayrollController::class);
        
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/download', [BackupController::class, 'backup'])->name('backup.download');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    });

    // Admin + Manager routes (inventory access)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('inventory', InventoryController::class);
        Route::get('inventory', function() {
            return redirect()->route('inventory.daily');
        })->name('inventory.index');
        Route::get('inventory-master', [InventoryController::class, 'index'])->name('inventory.master');
        Route::get('inventory-daily', [InventoryController::class, 'dailyEntry'])->name('inventory.daily');
        Route::post('inventory/{inventory}/log', [InventoryController::class, 'saveLog'])->name('inventory.saveLog');
        Route::get('inventory-history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::get('inventory-import', [InventoryController::class, 'importForm'])->name('inventory.import');
        Route::post('inventory-import', [InventoryController::class, 'importStore'])->name('inventory.import.store');
        Route::post('inventory-reorder', [InventoryController::class, 'reorder'])->name('inventory.reorder');
        Route::post('inventory-gas', [InventoryController::class, 'saveGas'])->name('inventory.saveGas');
        Route::post('inventory-electricity', [InventoryController::class, 'saveElectricity'])->name('inventory.saveElectricity');
        Route::post('inventory-oil', [InventoryController::class, 'saveOil'])->name('inventory.saveOil');
        Route::get('inventory-oil/monthly-detail', [InventoryController::class, 'oilMonthlyDetail'])->name('inventory.oilMonthlyDetail');
        Route::get('inventory-oil/monthly-report', [ReportController::class, 'monthlyOil'])->name('inventory.oilMonthlyReport');
        Route::get('inventory-low-stock', [InventoryController::class, 'getLowStock'])->name('inventory.lowStock');
    });

    // Admin + Accounts routes (expenses, sales, settlements)
    Route::middleware(['role:admin,accounts'])->group(function () {
        Route::resource('expenses', ExpenseController::class);
        Route::get('expenses/{expense}/payment-info', [ExpenseController::class, 'paymentInfo'])->name('expenses.payment-info');
        Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::get('sales/filter', [SaleController::class, 'filter'])->name('sales.filter');
        Route::resource('sales', SaleController::class);
        Route::post('sales-bulk', [SaleController::class, 'bulkStore'])->name('sales.bulkStore');
        Route::resource('settlements', SettlementController::class)->only(['index', 'update']);
        Route::post('settlements/generate', [SettlementController::class, 'generate'])->name('settlements.generate');
        Route::post('settlements/{settlement}/mark-received', [SettlementController::class, 'markReceived'])->name('settlements.markReceived');
        Route::post('credit-sales/{creditSale}/mark-received', [SettlementController::class, 'markCreditReceived'])->name('creditSales.markReceived');
    });
});