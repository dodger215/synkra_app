<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_subaccounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            
            // Banking Details
            $table->string('bank_code', 10); // Paystack bank code
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 20);
            $table->string('account_name', 255)->nullable();
            
            // Paystack Settings
            $table->decimal('percentage_charge', 5, 2)->default(0.00);
            $table->string('settlement_bank', 100)->nullable();
            $table->string('currency', 3)->default('NGN');
            
            // Paystack Response Data
            $table->string('subaccount_code', 100)->unique()->nullable();
            $table->unsignedBigInteger('paystack_subaccount_id')->nullable();
            
            // Status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Settlement Schedule
            $table->string('settlement_schedule', 50)->default('AUTO');
            
            // Metadata
            $table->jsonb('metadata')->nullable();
            
            // Foreign references
            $table->foreignUuid('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignUuid('store_id')->nullable()->constrained('ecommerce_stores')->nullOnDelete();
            
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_subaccounts');
    }
};
