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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_network_available')->default(false);
            $table->decimal('supply_price', 15, 2)->nullable();
            $table->decimal('supply_min_order', 15, 2)->default(1);
            $table->decimal('supply_max_order', 15, 2)->nullable();
            $table->decimal('supply_buffer_percent', 5, 2)->default(20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_network_available', 'supply_price', 'supply_min_order', 'supply_max_order', 'supply_buffer_percent']);
        });
    }
};
