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
        Schema::create('revenue_shares', function (Blueprint $table) {

                $table->id();
                $table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
                $table->enum('type', ['percentage','fixed']);
                $table->decimal('platform_share', 10, 2); // 5% or 100 KES
                $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_shares');
    }
};
