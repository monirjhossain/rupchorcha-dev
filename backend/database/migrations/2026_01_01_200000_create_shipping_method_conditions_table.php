<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('shipping_method_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_method_id');
            $table->string('condition_type'); // product, category, brand, min_order, free_shipping
            $table->string('condition_value')->nullable(); // id or value
            $table->timestamps();
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('shipping_method_conditions');
    }
};
