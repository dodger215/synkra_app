<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUuid('order_id')->nullable()->constrained('ecommerce_orders')->nullOnDelete();
            $table->integer('rating')->nullable(); // Should have check >=1 <=5 manually if using raw sql but simple integer is fine
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->jsonb('images')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->string('status', 50)->default('pending');
            $table->integer('helpful_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
