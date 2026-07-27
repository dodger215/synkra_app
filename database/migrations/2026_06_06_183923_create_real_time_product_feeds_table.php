<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('real_time_product_feeds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('platform_id')->constrained('marketing_platforms')->cascadeOnDelete();
            $table->string('feed_status', 50)->default('pending');
            $table->string('external_product_id')->nullable();
            $table->string('product_url', 500)->nullable();
            $table->string('sync_token')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->text('sync_error')->nullable();
            $table->boolean('is_live')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('real_time_product_feeds');
    }
};
