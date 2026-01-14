<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->json('credentials')->nullable();
            $table->string('status')->default('inactive');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
};
