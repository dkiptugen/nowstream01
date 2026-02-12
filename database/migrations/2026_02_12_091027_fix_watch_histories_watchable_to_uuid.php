<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watch_histories', function (Blueprint $table) {
            // Drop old morph columns (BIGINT)
            $table->dropIndex(['watchable_type', 'watchable_id']);
            $table->dropColumn(['watchable_id', 'watchable_type']);
        });

        Schema::table('watch_histories', function (Blueprint $table) {
            // Recreate as UUID morphs
            $table->uuid('watchable_id')->after('user_id');
            $table->string('watchable_type')->after('watchable_id');

            $table->index(['watchable_type', 'watchable_id']);
        });
    }

    public function down(): void
    {
        Schema::table('watch_histories', function (Blueprint $table) {
            $table->dropIndex(['watchable_type', 'watchable_id']);
            $table->dropColumn(['watchable_id', 'watchable_type']);
        });

        Schema::table('watch_histories', function (Blueprint $table) {
            // Revert back to BIGINT morphs
            $table->unsignedBigInteger('watchable_id')->after('user_id');
            $table->string('watchable_type')->after('watchable_id');

            $table->index(['watchable_type', 'watchable_id']);
        });
    }
};
