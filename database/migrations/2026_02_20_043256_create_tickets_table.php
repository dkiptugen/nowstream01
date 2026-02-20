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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->uuid('event_id');

            $table->string('ticket_number')->unique();
            $table->string('type')->nullable();
            $table->decimal('price', 10, 2)->nullable();

            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->foreign('event_id')
                ->references('uuid')
                ->on('events')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
