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
        Schema::create('stream_partner_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_partner_id')->index();
            $table->integer('owner_share');
            $table->integer('partner_share');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_partner_rates');
    }
};
