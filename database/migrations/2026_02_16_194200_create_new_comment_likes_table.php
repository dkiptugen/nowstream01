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
        // Drop old table if exists
       Schema::create('new_comment_likes', function (Blueprint $table) {
    $table->id();
    $table->char('comment_id', 36); // UUID
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['like', 'dislike']);
    $table->unique(['comment_id', 'user_id']);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_comment_likes');
    }
};
