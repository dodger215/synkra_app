<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subaccount_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subaccount_id')->constrained('tenant_subaccounts')->cascadeOnDelete();
            $table->string('verification_status', 50)->default('pending');
            $table->text('failure_reason')->nullable();
            $table->jsonb('bank_response')->nullable();
            $table->foreignUuid('attempted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('attempted_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subaccount_verifications');
    }
};
