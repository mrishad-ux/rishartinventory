<?php

// ADD THESE ROUTES to routes/web.php
// Replace the existing: Route::resource('inventory', InventoryController::class);
// With the following block:

Route::resource('inventory', InventoryController::class);
Route::get('inventory-daily', [InventoryController::class, 'dailyEntry'])->name('inventory.daily');
Route::post('inventory/{inventory}/log', [InventoryController::class, 'saveLog'])->name('inventory.saveLog');
Route::get('inventory-history', [InventoryController::class, 'history'])->name('inventory.history');
