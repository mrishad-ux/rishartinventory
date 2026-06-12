<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payroll')) {
            Schema::create('payroll', function (Blueprint $table) {
                $table->id();
                $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
                $table->date('payment_date');
                $table->integer('days_worked')->default(0);
                $table->decimal('basic_amount', 10, 2)->default(0);
                $table->decimal('bonus', 10, 2)->default(0);
                $table->decimal('deduction', 10, 2)->default(0);
                $table->decimal('net_amount', 10, 2)->default(0);
                $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
