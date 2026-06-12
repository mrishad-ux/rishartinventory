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
        // First, modify the enum column to add swiggy and zomato
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('sale_type', ['cash', 'gp', 'online', 'swiggy', 'zomato'])->change();
        });

        // Convert existing 'online' sales to 'swiggy' or 'zomato' based on platform
        \DB::statement("UPDATE sales SET sale_type = 'swiggy' WHERE sale_type = 'online' AND platform LIKE '%swiggy%'");
        \DB::statement("UPDATE sales SET sale_type = 'zomato' WHERE sale_type = 'online' AND platform LIKE '%zomato%'");
        // Default any remaining 'online' records to 'swiggy'
        \DB::statement("UPDATE sales SET sale_type = 'swiggy' WHERE sale_type = 'online'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: convert swiggy and zomato back to online
        \DB::statement("UPDATE sales SET sale_type = 'online' WHERE sale_type IN ('swiggy', 'zomato')");

        // Remove swiggy and zomato from enum
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('sale_type', ['cash', 'gp', 'online'])->change();
        });
    }
};
