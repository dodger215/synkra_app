<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_sets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('ad_set_name')->nullable();
            $table->string('bid_strategy', 50)->nullable();
            $table->decimal('bid_amount', 12, 2)->nullable();
            $table->jsonb('audience')->nullable();
            $table->jsonb('placements')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('external_ad_set_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_sets');
    }
};
