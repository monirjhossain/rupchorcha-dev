<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('tracking_url')->nullable();
            $table->boolean('status')->default(true); // Active/Inactive
            $table->string('logo')->nullable();
            $table->string('service_area')->nullable(); // Dhaka, Outside Dhaka, Nationwide, etc.
            $table->string('delivery_types')->nullable(); // Regular, Express, COD, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        // Add courier_id to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->nullable()->after('id');
            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('set null');
        });
    }
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_id']);
            $table->dropColumn('courier_id');
        });
        Schema::dropIfExists('couriers');
    }
};
