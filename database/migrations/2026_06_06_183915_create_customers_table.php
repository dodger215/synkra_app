<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->string('customer_group', 50)->default('retail');
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('average_order_value', 12, 2)->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->timestamp('first_order_at')->nullable();
            $table->decimal('lifetime_value', 12, 2)->nullable();
            $table->integer('loyalty_points')->default(0);
            $table->string('tier', 50)->default('bronze');
            $table->jsonb('tags')->nullable();
            $table->jsonb('custom_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
