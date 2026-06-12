<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->decimal('oil_l1_packets', 10, 2)->nullable()->after('electricity_reading');
            $table->decimal('oil_l2_packets', 10, 2)->nullable()->after('oil_l1_packets');
            $table->decimal('oil_r1_packets', 10, 2)->nullable()->after('oil_l2_packets');
            $table->decimal('oil_r2_packets', 10, 2)->nullable()->after('oil_r1_packets');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropColumn(['oil_l1_packets', 'oil_l2_packets', 'oil_r1_packets', 'oil_r2_packets']);
        });
    }
};