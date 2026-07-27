<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecommerce_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained('ecommerce_stores')->cascadeOnDelete();
            $table->string('page_name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('page_type', 50)->nullable(); // 'home', 'product', etc.
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->jsonb('content')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('publish_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_pages');
    }
};
