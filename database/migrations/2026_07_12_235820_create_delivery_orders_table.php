<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ecommerce_order_id')->constrained('ecommerce_orders')->cascadeOnDelete();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('provider'); // bolt, yango
            $table->string('external_id')->nullable();
            $table->string('quote_id')->nullable();
            $table->string('status')->default('created');
            $table->decimal('fee', 10, 2);
            $table->string('currency')->default('GHS');
            $table->string('tracking_url')->nullable();

            $table->string('pickup_address')->nullable();
            $table->decimal('pickup_lat', 10, 8)->nullable();
            $table->decimal('pickup_lng', 11, 8)->nullable();

            $table->string('dropoff_address')->nullable();
            $table->decimal('dropoff_lat', 10, 8)->nullable();
            $table->decimal('dropoff_lng', 11, 8)->nullable();

            $table->string('courier_name')->nullable();
            $table->string('courier_phone')->nullable();
            $table->integer('estimated_minutes')->nullable();

            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
