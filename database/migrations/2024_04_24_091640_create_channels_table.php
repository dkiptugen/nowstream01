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
                Schema::create('channels', function (Blueprint $table)
                    {
                        $table->uuid()->primary();
                        $table->ulid('identifier')->index('ulid');
                        $table->string('name');
                        $table->longText ('thumbnail');
						$table->longText('cover_image');
                        $table->longText('description');
                        $table->tinyInteger('status');
                        $table->unsignedBigInteger('stream_partner_id');
                        $table->softDeletes();
                        $table->timestamps();
                        //$table->foreign('stream_partner_id')->references('id')->on('stream_partners')->cascadeOnDelete()->cascadeOnUpdate();

                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('channels');
            }
        };
