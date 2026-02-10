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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('slug')->index();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->tinyInteger('top_menu')->default(0);
            $table->uuid('parent_id')->nullable();
            $table->tinyInteger('is_brand')->default(0);
            $table->longText('thumburl')->nullable();
            $table->text('type')->nullable();
            $table->integer('position')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
