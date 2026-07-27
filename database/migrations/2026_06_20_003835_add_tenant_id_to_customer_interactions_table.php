<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_interactions', function (Blueprint $table) {
            $table->foreignUuid('tenant_id')->nullable()->after('id')->constrained('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customer_interactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
        });
    }
};
