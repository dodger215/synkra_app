<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('transaction_reference', 100)->unique();
            $table->string('reference_type', 50)->nullable(); // e.g., 'pos_order', 'ecommerce_order', 'invoice'
            $table->uuid('reference_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method', 50)->nullable(); // 'card', 'cash', 'transfer', 'crypto'
            $table->string('payment_gateway', 50)->nullable(); // 'stripe', 'paystack', 'paypal'
            $table->string('status', 50)->default('pending'); // 'pending', 'successful', 'failed', 'refunded'
            $table->jsonb('gateway_response')->nullable();
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
