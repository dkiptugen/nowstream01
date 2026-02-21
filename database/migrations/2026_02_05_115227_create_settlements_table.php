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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->uuid('microsite_id')->index();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('organizer_amount', 10, 2);
            $table->enum('status', ['pending','paid']);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'microsite_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
