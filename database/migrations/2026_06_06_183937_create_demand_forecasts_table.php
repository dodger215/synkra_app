<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demand_forecasts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->date('forecast_date')->nullable();
            $table->integer('forecast_quantity')->nullable();
            $table->integer('confidence_lower')->nullable();
            $table->integer('confidence_upper')->nullable();
            $table->string('model_version', 50)->nullable();
            $table->jsonb('factors')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demand_forecasts');
    }
};
