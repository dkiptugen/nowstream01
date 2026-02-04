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
                Schema::create('rates', function (Blueprint $table)
                    {
                        $table->id();
                        $table->string('name');
                        $table->longText('description');
                        $table->decimal('cost')->nullable();
                        $table->decimal('reserved_currency_cost')->nullable();
                        $table->uuid('content_id');
                        $table->foreign('content_id')->references('uuid')->on('contents')->CascadeOnDelete()->CascadeOnUpdate();
                        $table->timestamps();
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('rates');
            }
        };
