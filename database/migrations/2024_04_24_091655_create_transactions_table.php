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
                Schema::create('transactions', function (Blueprint $table)
                    {
                        $table->id();
                        $table->unsignedBigInteger('user_id');
						$table->string('name')->nullable();
                        $table->string('payment_method');
                        $table->decimal('cost');
	                    $table->string('transaction_token')->nullable();
	                    $table->string('subscription_token')->nullable();
                        $table->uuid('subscription_id')->index();
	                    $table->string('receipt')->unique('receipt')->nullable ();
                        $table->decimal('amount_paid')->nullable ();
                        $table->uuid('event_id')->index()->nullable();
	                    $table->uuid('channel_id')->index()->nullable();;
						$table->dateTime('date_paid')->nullable();
						$table->text('response')->nullable();
                        $table->timestamps();
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('transactions');
            }
        };
