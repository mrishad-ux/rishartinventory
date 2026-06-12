<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inventory_items')) {
            if (!Schema::hasColumn('inventory_items', 'category')) {
                Schema::table('inventory_items', function (Blueprint $table) {
                    $table->string('category')->default('other')->after('name');
                });
            }
            if (!Schema::hasColumn('inventory_items', 'minimum_stock_qty')) {
                Schema::table('inventory_items', function (Blueprint $table) {
                    $table->decimal('minimum_stock_qty', 10, 2)->default(0)->after('minimum_stock')->nullable();
                });
            }
            if (!Schema::hasColumn('inventory_items', 'is_mayo')) {
                Schema::table('inventory_items', function (Blueprint $table) {
                    $table->boolean('is_mayo')->default(false)->after('minimum_stock_qty');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inventory_items')) {
            if (Schema::hasColumn('inventory_items', 'category')) {
                Schema::table('inventory_items', function (Blueprint $table) {
                    $table->dropColumn('category');
                });
            }
        }
    }
};
