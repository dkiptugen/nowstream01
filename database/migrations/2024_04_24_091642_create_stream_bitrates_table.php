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
        Schema::create('content_bitrates', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('content_id');
            $table->string('resolution');
            $table->integer('bitrate');
            $table->string('url');
            $table->timestamps();
            $table->foreign('content_id')->references('uuid')->on('contents')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_bitrates');
    }
};
