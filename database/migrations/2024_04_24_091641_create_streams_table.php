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
                Schema::create('streams', function (Blueprint $table)
                    {
                        $table->id();
                        $table->ulid('identifier')->index();
                        $table->string('slug')->unique();
                        $table->string('title');
                        $table->longText('description');
                        $table->string('thumbnail_url');
                        $table->string('stream_key')->unique();
                        $table->text('stream_url');
	                    $table->text('stream_video_link');
                        $table->dateTime('start_time')->nullable();
                        $table->dateTime('end_time')->nullable();
                        $table->unsignedBigInteger('event_id');
                        $table->unsignedBigInteger('system_user_id');
                        $table->unsignedBigInteger('channel_id')->index();
						$table->tinyInteger ('status')->default(0);
                        $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->foreign('system_user_id')->references('id')->on('system_users')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->timestamps();
						$table->softDeletes ();
						
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('streams');
            }
        };
