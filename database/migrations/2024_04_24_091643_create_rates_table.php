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
                        $table->unsignedBigInteger('stream_id');
                        $table->foreign('stream_id')->references('id')->on('streams')->CascadeOnDelete()->CascadeOnUpdate();
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
