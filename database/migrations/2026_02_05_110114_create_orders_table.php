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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['product','content'])->default('product');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('fees', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('KES');
            $table->tinyInteger('is_subscribable')->default(0);
            $table->tinyInteger('is_recurrent')->default(0);
            $table->dateTime('next_payment')->nullable();
            $table->dateTime('subscription_start_at')->nullable();
            $table->dateTime('subscription_end_at')->nullable();
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->unsignedBigInteger('latest_transaction_id')->nullable();
            $table->string('subscription_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
