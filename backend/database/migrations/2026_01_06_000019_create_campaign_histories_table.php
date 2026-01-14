<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('campaign_histories', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_type');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->json('recipients');
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('campaign_histories');
    }
};
