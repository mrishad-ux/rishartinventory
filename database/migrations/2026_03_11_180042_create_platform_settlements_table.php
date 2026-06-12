<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('platform_settlements')) {
            Schema::create('platform_settlements', function (Blueprint $table) {
                $table->id();
                $table->enum('platform', ['swiggy', 'zomato']);
                $table->date('period_from');
                $table->date('period_to');
                $table->date('expected_credit_date');
                $table->date('actual_credit_date')->nullable();
                $table->decimal('gross_amount', 10, 2)->default(0);
                $table->decimal('estimated_commission', 10, 2)->default(0);
                $table->decimal('estimated_net', 10, 2)->default(0);
                $table->decimal('actual_amount_received', 10, 2)->nullable();
                $table->decimal('actual_commission', 10, 2)->nullable();
                $table->enum('status', ['pending', 'received', 'disputed'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settlements');
    }
};
