<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Bkash, Nagad, SSLCommerz
            $table->string('slug')->unique(); // e.g. bkash, nagad, sslcommerz
            $table->json('config')->nullable(); // API credentials/config
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('payment_gateways');
    }
};
