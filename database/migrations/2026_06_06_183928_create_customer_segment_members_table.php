<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_segment_members', function (Blueprint $table) {
            $table->foreignUuid('segment_id')->constrained('customer_segments')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->timestamp('added_at')->nullable();
            $table->primary(['segment_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_segment_members');
    }
};
