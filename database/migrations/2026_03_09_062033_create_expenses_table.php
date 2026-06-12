<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('category');
                $table->decimal('amount', 10, 2);
                $table->date('expense_date');
                $table->enum('payment_type', ['cash', 'credit'])->default('cash');
                $table->enum('status', ['paid', 'unpaid'])->default('paid');
                $table->date('due_date')->nullable();
                $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
