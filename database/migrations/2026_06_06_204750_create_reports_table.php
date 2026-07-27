<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('report_name')->nullable();
            $table->string('report_type', 50)->nullable();
            $table->jsonb('parameters')->nullable();
            $table->string('schedule', 100)->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->string('last_run_status', 50)->nullable();
            $table->jsonb('recipients')->nullable(); // Arrays are best stored as JSONB or text depending on needs, Laravel cast array uses JSON under the hood. Postgres text[] also an option but JSONB is simpler to cast. Let's use jsonb for array of emails.
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
