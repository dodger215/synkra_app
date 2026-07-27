<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('return_number', 100)->unique()->nullable();
            $table->string('original_reference_type', 50)->nullable();
            $table->uuid('original_reference_id')->nullable();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('location_id')->constrained('stock_locations')->cascadeOnDelete();
            $table->integer('quantity')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('condition', 50)->nullable();
            $table->integer('restocked_quantity')->default(0);
            $table->timestamp('restocked_at')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 50)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_returns');
    }
};
