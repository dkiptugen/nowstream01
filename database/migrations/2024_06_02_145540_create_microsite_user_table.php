<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicrositeUserTable extends Migration
{
    public function up()
    {
        Schema::create('microsite_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('microsite_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('microsite_user');
    }
}
