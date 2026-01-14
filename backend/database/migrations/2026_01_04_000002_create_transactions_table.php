<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('payment_gateway_id');
            $table->string('transaction_id')->nullable(); // gateway txn id
            $table->string('status')->default('pending'); // pending, success, failed
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('BDT');
            $table->json('payload')->nullable(); // raw gateway response
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('transactions');
    }
};
