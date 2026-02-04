<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration
		{
		/**
		 * Run the migrations.
		 */
			public function up ()
			: void
				{
					Schema::create ('system_user_channel', function (Blueprint $table)
						{
							$table->id ();
							$table->unsignedBigInteger ('system_user_id');
							$table->uuid ('channel_id')->index()->nullable();
							$table->foreign ('system_user_id')->references ('id')->on ('system_users')->cascadeOnDelete ()->cascadeOnUpdate ()
							;
							$table->foreign ('channel_id')->references ('uuid')->on ('channels')->cascadeOnDelete ()->cascadeOnUpdate ()
							;
							$table->unsignedBigInteger ('created_by')->index ();
							$table->timestamps ();
						});
				}

		/**
		 * Reverse the migrations.
		 */
			public function down ()
			: void
				{
					Schema::dropIfExists ('system_user_channel');
				}
		};
