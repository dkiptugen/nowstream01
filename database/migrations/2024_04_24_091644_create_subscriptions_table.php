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
	            Schema::create('subscriptions', function (Blueprint $table) {
		            $table->id();
                    $table->string('identifier')->unique('identifier');
		            $table->string('stream_token');
		            $table->unsignedBigInteger('user_id');
		            $table->enum('type', ['video', 'stream']);
		            $table->decimal('cost', 8, 2);
                    $table->decimal('amount_paid')->nullable ();
                    $table->decimal('balance')->nullable ();
		            $table->tinyInteger('status');
		           
		            $table->unsignedBigInteger('event_id')->index();
		            $table->unsignedBigInteger('channel_id')->index();
		            $table->unsignedBigInteger('activated_by')->nullable();
		            $table->text('activation_reason')->nullable();
		            $table->timestamps();
	            });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('subscriptions');
            }
        };
