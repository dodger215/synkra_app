<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->string('mfa_type')->default('none')->after('password'); // none, email, sms
            $table->string('mfa_code')->nullable()->after('mfa_type');
            $table->timestamp('mfa_expires_at')->nullable()->after('mfa_code');
            $table->boolean('mfa_verified')->default(false)->after('mfa_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'mfa_type', 'mfa_code', 'mfa_expires_at', 'mfa_verified']);
        });
    }
};
