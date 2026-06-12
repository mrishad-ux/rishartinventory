<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->date('sale_date');
                $table->enum('sale_type', ['cash', 'gp', 'online']);
                $table->string('platform')->nullable();
                $table->decimal('gross_amount', 10, 2);
                $table->decimal('commission_percent', 5, 2)->default(0);
                $table->decimal('commission_amount', 10, 2)->default(0);
                $table->decimal('net_amount', 10, 2);
                $table->enum('settlement_status', ['not_applicable', 'pending', 'received'])->default('not_applicable');
                $table->date('expected_settlement_date')->nullable();
                $table->date('actual_settlement_date')->nullable();
                $table->string('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
