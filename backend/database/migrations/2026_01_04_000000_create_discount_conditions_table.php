<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('discount_conditions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['product', 'brand', 'category']);
            $table->unsignedBigInteger('target_id'); // product_id, brand_id, or category_id
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping']);
            $table->decimal('discount_value', 8, 2)->nullable(); // null for free shipping
            $table->boolean('free_shipping')->default(false);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('discount_conditions');
    }
};
