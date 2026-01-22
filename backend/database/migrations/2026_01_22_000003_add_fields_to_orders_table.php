<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add courier relationship
            if (!Schema::hasColumn('orders', 'courier_id')) {
                $table->unsignedBigInteger('courier_id')->nullable()->after('transaction_id');
                $table->foreign('courier_id')->references('id')->on('couriers')->nullOnDelete();
            }
            
            // Add tracking information
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('courier_id');
            }
            
            // Add customer notes (separate from admin_note)
            if (!Schema::hasColumn('orders', 'customer_notes')) {
                $table->longText('customer_notes')->nullable()->after('notes');
            }
            
            // Add invoice tracking
            if (!Schema::hasColumn('orders', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->unique()->after('customer_notes');
            }
            
            if (!Schema::hasColumn('orders', 'invoice_sent_at')) {
                $table->timestamp('invoice_sent_at')->nullable()->after('invoice_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'notes',
                'customer_notes',
                'invoice_number',
                'invoice_sent_at',
            ]);
        });
    }
};
