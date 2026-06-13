<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_platforms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('platform_name', 100)->unique();
            $table->jsonb('api_config_template')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_platforms');
    }
};
