<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->decimal('oil_mayo_packets', 10, 2)->nullable()->after('oil_r2_packets');
            $table->decimal('oil_sauces_packets', 10, 2)->nullable()->after('oil_mayo_packets');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropColumn(['oil_mayo_packets', 'oil_sauces_packets']);
        });
    }
};