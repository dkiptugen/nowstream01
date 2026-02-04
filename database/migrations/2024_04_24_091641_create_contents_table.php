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
                Schema::create('contents', function (Blueprint $table)
                    {
                        $table->uuid()->primary();
                        $table->uuid('parent_id')->index()->nullable();
                        $table->string('slug')->unique();
                        $table->string('title');
                        $table->longText('description');
                        $table->string('thumbnail_url');
                        $table->enum('content_group', ['livestream', 'video','podcast','tv','radio'])->default('livestream');
                        $table->string('duration')->nullable();
                        $table->string('type')->nullable();
                        $table->string('stream_key')->unique();
                        $table->text('stream_url')->nullable();
	                    $table->text('stream_video_link')->nullable();
                        $table->text('content_path')->nullable();
                        $table->dateTime('start_time')->nullable();
                        $table->dateTime('end_time')->nullable();
                        $table->uuid('event_id')->index();
                        $table->unsignedBigInteger('system_user_id');
                        $table->unsignedBigInteger('channel_id')->index();
						$table->tinyInteger ('status')->default(0);
                        $table->foreign('system_user_id')->references('id')->on('system_users')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->unsignedInteger('viewers')->default(0);
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
                Schema::dropIfExists('contents');
            }
        };
