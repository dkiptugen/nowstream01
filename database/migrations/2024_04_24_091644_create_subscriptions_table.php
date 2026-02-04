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
		            $table->uuid()->primary();
		            $table->string('stream_token');
		            $table->unsignedBigInteger('user_id');
		            $table->enum('type', ['video', 'stream']);
		            $table->decimal('cost', 8, 2);
                    $table->decimal('amount_paid')->nullable ();
                    $table->decimal('balance')->nullable ();
		            $table->tinyInteger('status');
                    $table->uuid('event_id')->index();
		            $table->uuid('channel_id')->index();
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
