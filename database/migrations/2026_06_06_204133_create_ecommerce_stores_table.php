<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecommerce_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('store_name');
            $table->string('domain')->unique()->nullable();
            $table->text('logo_url')->nullable();
            $table->text('favicon_url')->nullable();
            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('theme_id', 100)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('language', 10)->default('en');
            $table->boolean('is_published')->default(false);
            $table->jsonb('seo_settings')->nullable();
            $table->jsonb('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_stores');
    }
};
