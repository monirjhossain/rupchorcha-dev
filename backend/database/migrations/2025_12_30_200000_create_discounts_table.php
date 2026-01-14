<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['bogo', 'combo', 'category', 'brand', 'product']);
            $table->json('product_ids')->nullable();
            $table->json('combo_product_ids')->nullable();
            $table->json('category_ids')->nullable();
            $table->json('brand_ids')->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->integer('min_quantity')->nullable();
            $table->date('start_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('discounts');
    }
};
