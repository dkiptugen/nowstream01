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
                        $table->text('old_id')->nullable();
                        $table->string('slug')->unique();
                        $table->string('title');
                        $table->longText('description')->nullable();
                        $table->string('thumbnail_url')->nullable();
                        $table->enum('content_group', ['livestream', 'video','podcast','podcast_episode','tv','radio'])->default('livestream');
                        $table->string('duration')->nullable();
                        $table->string('type')->nullable();
                        $table->string('stream_key')->nullable()->index();
                        $table->text('stream_url')->nullable();
	                    $table->text('stream_video_link')->nullable();
                        $table->text('content_path')->nullable();
                        $table->dateTime('start_time')->nullable();
                        $table->dateTime('end_time')->nullable();
                        $table->uuid('event_id')->index()->nullable();
                        $table->unsignedBigInteger('system_user_id');
                        $table->unsignedBigInteger('language_id')->index('language_id')->nullable();
                        $table->unsignedBigInteger('region_id')->index('region_id')->nullable();
                        $table->string('country')->nullable();
                        $table->string('source')->nullable();
                        $table->string('language')->nullable();
                        $table->string('author')->nullable();
                        $table->uuid('category_id')->index()->nullable();
                        $table->uuid('channel_id')->index()->nullable();
						$table->tinyInteger ('status')->default(0);
                        $table->text('genre')->nullable();
                        $table->foreign('system_user_id')->references('id')->on('system_users')->cascadeOnDelete()->cascadeOnUpdate();
                        $table->unsignedInteger('viewers')->default(0);
                        $table->dateTime('publishdate')->nullable();
                        $table->dateTime('last_published')->nullable();
                        $table->tinyInteger('is_explicit')->default(0);
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
