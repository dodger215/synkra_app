<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_performances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ad_set_id')->constrained('ad_sets')->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('ctr', 8, 4)->nullable();
            $table->decimal('spend', 12, 2)->nullable();
            $table->integer('conversions')->default(0);
            $table->decimal('conversion_value', 12, 2)->nullable();
            $table->decimal('roas', 8, 4)->nullable();
            $table->decimal('cost_per_conversion', 12, 2)->nullable();
            $table->decimal('frequency', 5, 2)->nullable();
            $table->integer('reach')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_performances');
    }
};
