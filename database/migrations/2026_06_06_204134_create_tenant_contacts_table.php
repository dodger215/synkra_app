<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('contact_type', 50)->nullable(); // e.g., 'primary', 'billing', 'support'
            $table->string('platform', 50)->nullable(); // e.g., 'facebook', 'twitter', 'instagram', 'linkedin'
            $table->string('handle')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_contacts');
    }
};
