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
        Schema::dropIfExists('new_comment_likes');

        // Create the new table with UUID support for comment_id
        Schema::create('new_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->char('comment_id', 36); // UUID column
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['like', 'dislike']);
            $table->unique(['comment_id', 'user_id']);
            $table->timestamps();

            // Optional: add foreign key if your comments table uses UUID as primary
            // $table->foreign('comment_id')->references('uuid')->on('comments')->cascadeOnDelete();
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
