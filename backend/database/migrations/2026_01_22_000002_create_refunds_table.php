<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('method')->nullable(); // original, bkash, nagad, card, store_credit
            $table->string('status')->default('pending'); // pending, approved, rejected, processed
            $table->string('transaction_id')->nullable();
            $table->text('reason')->nullable();
            $table->json('meta')->nullable(); // gateway payload or notes
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
