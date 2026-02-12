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
{
    Schema::table('favorites', function (Blueprint $table) {
        $table->renameColumn('content_id', 'content_uuid');
    });
}

public function down()
{
    Schema::table('favorites', function (Blueprint $table) {
        $table->renameColumn('content_uuid', 'content_id');
    });
}

};
