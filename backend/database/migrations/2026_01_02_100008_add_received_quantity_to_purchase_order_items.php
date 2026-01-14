<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->integer('received_quantity')->default(0)->after('quantity');
            $table->date('received_date')->nullable()->after('received_quantity');
            $table->unsignedBigInteger('received_by')->nullable()->after('received_date');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['received_by']);
            $table->dropColumn(['received_quantity', 'received_date', 'received_by']);
        });
    }
};
