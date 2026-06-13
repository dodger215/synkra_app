<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subaccount_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subaccount_id')->constrained('tenant_subaccounts')->cascadeOnDelete();
            $table->string('transaction_reference', 255)->unique()->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('fee_charged', 12, 2)->nullable();
            $table->decimal('net_amount', 12, 2)->nullable();
            $table->string('status', 50)->default('pending');
            $table->string('paystack_transfer_code', 255)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subaccount_transactions');
    }
};
