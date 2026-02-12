<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            
            if (Schema::hasColumn('favorites', 'content_id')) {
                $table->renameColumn('content_id', 'content_uuid');
            }
 
            $table->uuid('content_uuid')->change();
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->renameColumn('content_uuid', 'content_id');
            $table->unsignedBigInteger('content_id')->change();
        });
    }
};

