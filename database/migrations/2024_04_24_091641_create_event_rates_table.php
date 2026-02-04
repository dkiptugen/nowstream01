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
                Schema::create('event_rates', function (Blueprint $table)
                    {
                        $table->id();
                        $table->uuid('event_id')->index();
                        $table->string('name');
                        $table->decimal('cost')->nullable();
	                    $table->string('reserved_currency')->nullable();
                        $table->decimal('reserved_currency_cost')->nullable();
                        $table->tinyInteger('status')->default(0);
						$table->dateTime('date_from')->nullable();
						$table->dateTime('date_to')->nullable();
                        $table->timestamps();
                        $table->foreign('event_id')
                              ->references('uuid')
                                 ->on('events')
                                ->cascadeOnUpdate()
                                ->cascadeOnDelete();
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('event_rates');
            }
        };
