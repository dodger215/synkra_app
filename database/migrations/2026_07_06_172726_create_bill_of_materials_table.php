<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 12, 4)->default(1);
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('bom_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bill_of_material_id')->constrained('bill_of_materials')->cascadeOnDelete();
            $table->foreignUuid('component_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 12, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('bill_of_materials');
    }
};
