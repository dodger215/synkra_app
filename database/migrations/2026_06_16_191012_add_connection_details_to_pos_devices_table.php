<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->string('connection_type', 20)->default('network')->after('serial_number');
            $table->string('ip_address', 45)->nullable()->after('connection_type');
            $table->integer('port')->default(9100)->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('pos_devices', function (Blueprint $table) {
            $table->dropColumn(['connection_type', 'ip_address', 'port']);
        });
    }
};
