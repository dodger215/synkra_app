<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_name')->nullable();
            $table->string('metric_category', 100)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->decimal('previous_value', 12, 2)->nullable();
            $table->decimal('target_value', 12, 2)->nullable();
            $table->string('unit', 50)->nullable();
            $table->timestamp('measured_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_metrics');
    }
};
