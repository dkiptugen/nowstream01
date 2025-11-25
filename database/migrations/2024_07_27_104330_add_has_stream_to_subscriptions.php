<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->tinyInteger ('has_stream')->default (1);
	        $table->tinyInteger ('has_video')->default (0);
        });
	    Schema::table('event_rates', function (Blueprint $table) {
		    $table->tinyInteger ('has_stream')->default (1);
		    $table->tinyInteger ('has_video')->default (0);
		    $table->tinyInteger ('visible')->default (0);
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn (['has_stream','has_video']);
        });
	    Schema::table('event_rates', function (Blueprint $table) {
		    $table->dropColumn (['has_stream','has_video','visible']);
	    });
    }
};
