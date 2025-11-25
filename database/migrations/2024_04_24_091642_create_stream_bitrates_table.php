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
        Schema::create('stream_bitrates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id');
            $table->string('resolution');
            $table->integer('bitrate');
            $table->string('url');
            $table->timestamps();
            $table->foreign('stream_id')->references('id')->on('streams')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_bitrates');
    }
};
