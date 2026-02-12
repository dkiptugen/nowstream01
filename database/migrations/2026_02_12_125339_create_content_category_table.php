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
        Schema::create('content_category', function (Blueprint $table) {
            $table->id();

            $table->uuid('content_id');
            $table->uuid('category_id');
            $table->timestamps();
            $table->foreign('content_id')
                  ->references('uuid')
                  ->on('contents')
                  ->cascadeOnDelete();
            $table->foreign('category_id')
                  ->references('uuid')
                  ->on('categories')
                  ->cascadeOnDelete();
            $table->unique(['content_id', 'category_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_category');
    }
};
