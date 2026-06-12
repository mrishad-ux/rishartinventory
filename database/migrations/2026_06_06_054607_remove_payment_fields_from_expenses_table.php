<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'credit'])->default('cash');
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
            $table->date('due_date')->nullable();
        });
    }
};