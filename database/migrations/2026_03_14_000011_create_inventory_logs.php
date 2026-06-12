<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inventory_logs')) {
            Schema::create('inventory_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
                $table->date('log_date');
                $table->decimal('opening', 10, 2)->default(0);
                $table->decimal('purchased', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->storedAs('opening + purchased');
                $table->decimal('consumption', 10, 2)->default(0);
                $table->decimal('wastage', 10, 2)->default(0);
                $table->decimal('closing', 10, 2)->storedAs('opening + purchased - consumption - wastage');
                $table->decimal('mayo_oil_qty', 10, 2)->nullable();
                $table->decimal('mayo_milk_qty', 10, 2)->nullable();
                $table->decimal('mayo_bottles', 10, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['inventory_item_id', 'log_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
