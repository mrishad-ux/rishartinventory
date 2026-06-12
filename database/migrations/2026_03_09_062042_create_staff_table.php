<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('role');
                $table->string('phone')->nullable();
                $table->enum('salary_type', ['daily', 'monthly'])->default('monthly');
                $table->decimal('salary_amount', 10, 2)->default(0);
                $table->date('joining_date')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
