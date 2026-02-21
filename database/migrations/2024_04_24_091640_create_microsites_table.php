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
                Schema::create('microsites', function (Blueprint $table)
                    {
                        $table->uuid()->primary();
                        $table->string('name');
                        $table->string('domain');
                        $table->string('slug');
                        $table->string('logo')->nullable();
                        $table->text('colorscheme')->comment('JSON');
                        $table->string('banner')->nullable();
                        $table->string('cover')->nullable();
                        $table->string('favicon')->nullable();
                        $table->string('description');
                        $table->string('keywords')->nullable();
                        $table->text('social_links')->nullable();
                        $table->bigInteger('views')->default(0)->comment('Views');
                        $table->bigInteger('followers')->default(0);
                        $table->tinyInteger('status')->default(0);
                        $table->tinyInteger('visible')->default(0);
                        $table->tinyInteger('verified')->default(0);
                        $table->unsignedBigInteger('system_user_id');
                        $table->timestamps();
                        $table->softDeletes();

                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('microsites');
            }
        };
