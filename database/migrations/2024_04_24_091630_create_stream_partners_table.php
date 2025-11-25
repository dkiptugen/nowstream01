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
                Schema::create('stream_partners', function (Blueprint $table)
                    {
                        $table->id();
                        $table->string('name')->index();
                        $table->string('reg_no')->unique();
                        $table->text('address');
                        $table->date('registration_date');
                        $table->string('pin_no');
                        $table->json('legal_documents')->nullable();
                        $table->unsignedBigInteger('system_user_id')->index();
                        //$table->unsignedBigInteger('stream_partner_rate_id')->index();
                        $table->softDeletes();
                        $table->timestamps();
                    });
            }

        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('stream_partners');
            }
        };
