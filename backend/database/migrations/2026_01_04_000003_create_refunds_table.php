<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->enum('type', ['refund', 'return', 'exchange']);
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('reason')->nullable();
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }
    public function down() {
        Schema::dropIfExists('refunds');
    }
};
