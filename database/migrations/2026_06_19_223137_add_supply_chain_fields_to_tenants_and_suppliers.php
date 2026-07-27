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
        Schema::table('tenants', function (Blueprint $table) {
            // mode can be 'buyer', 'supplier', or 'both'
            $table->string('supply_chain_mode')->default('buyer')->after('name');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            // Link to another tenant if they are on flowexa
            $table->foreignUuid('supplier_tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->string('connection_status')->default('none'); // none, pending, approved, rejected
        });

        Schema::table('reorder_alerts', function (Blueprint $table) {
            $table->foreignUuid('suggested_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('supply_chain_mode');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_tenant_id');
            $table->dropColumn('connection_status');
        });

        Schema::table('reorder_alerts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('suggested_supplier_id');
        });
    }
};
