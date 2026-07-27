<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->jsonb('items')->nullable();
            $table->string('coupon_code', 100)->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('abandoned_email_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
