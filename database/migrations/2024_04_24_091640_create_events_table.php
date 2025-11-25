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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('event_name');
            $table->text('description')->nullable();
			$table->text('event_image');
			$table->string('venue')->nullable();
            $table->dateTime('publish_date');
			$table->dateTime('start_time');
	        $table->dateTime('end_time');
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('system_user_id');
            $table->unsignedInteger('channel_id');
			$table->tinyInteger ('is_featured')->default (0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
