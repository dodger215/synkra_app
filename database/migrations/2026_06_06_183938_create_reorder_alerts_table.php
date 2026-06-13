<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reorder_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('location_id')->nullable()->constrained('stock_locations')->nullOnDelete();
            $table->string('alert_type', 50)->nullable();
            $table->integer('current_quantity')->nullable();
            $table->integer('threshold')->nullable();
            $table->integer('suggested_order_quantity')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reorder_alerts');
    }
};
