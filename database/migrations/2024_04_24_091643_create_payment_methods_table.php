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
                Schema::create('payment_methods', function (Blueprint $table)
                    {
                        $table->id();
                        $table->string('provider');
                        $table->string('name');
                        $table->string('identifier');
                        $table->string('type');
                        $table->longText('configuration');
                        $table->tinyInteger('status')->default(0);
                        $table->tinyInteger('notifying')->default(0);
                        $table->longText('notification_endpoints');
                        $table->unsignedBigInteger('system_user_id');
                        $table->timestamps();
                        $table->foreign('system_user_id')->references('id')->on('system_users')->CascadeOnDelete()->CascadeOnUpdate();
                        
                    });
            }
        
        /**
         * Reverse the migrations.
         */
            public function down()
            : void
            {
                Schema::dropIfExists('payment_methods');
            }
        };
