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
        // SQLite workaround: drop views that depend on the table before altering
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS stock_alerts;");
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS inventory_valuation;");

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignUuid('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            }
        });

        // Recreate views
        $viewsMigration = new (include database_path('migrations/2026_06_06_204750_create_inventory_views.php'));
        $viewsMigration->up();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_id');
        });
    }
};
