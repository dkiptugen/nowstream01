<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
        {
        /**
         * Run the migrations.
         */
            public function up()
            : void
            {
                Schema::create('videos', function (Blueprint $table)
                    {
                        $table->id();
	                    $table->unsignedBigInteger('channel_id');
                        $table->unsignedBigInteger('event_id');
                        $table->string('slug');
                        $table->string('title');
                        $table->text('description');
                        $table->longText('thumbnail');
                        $table->text('video_path');
	                    $table->json('tags')->nullable();
                        $table->unsignedBigInteger('system_user_id');
                        $table->foreign('system_user_id')->references('id')->on('system_users')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->timestamps();
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('stream_videos');
            }
        };
