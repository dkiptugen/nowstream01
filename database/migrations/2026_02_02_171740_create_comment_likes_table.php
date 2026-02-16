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
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();

            // UUID for comment_id (references comments.uuid)
            $table->uuid('comment_id');
            $table->foreign('comment_id')
                  ->references('uuid')
                  ->on('comments')
                  ->onDelete('cascade');

            // user_id FK
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Like / Dislike type
            $table->enum('type', ['like', 'dislike']);

            // Ensure a user can like/dislike a comment only once
            $table->unique(['comment_id', 'user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};
