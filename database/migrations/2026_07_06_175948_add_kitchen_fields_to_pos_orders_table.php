<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_orders', function (Blueprint $table) {
            $table->string('status', 50)->default('completed')->after('order_type');
            $table->foreignUuid('pos_table_id')->nullable()->after('customer_id')->constrained('pos_tables')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pos_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pos_table_id');
            $table->dropColumn(['status', 'pos_table_id']);
        });
    }
};
