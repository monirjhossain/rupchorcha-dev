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
        Schema::table('product_reviews', function (Blueprint $table) {
            // Index for finding reviews by product
            $table->index('product_id');
            
            // Index for finding reviews by user
            $table->index('user_id');
            
            // Index for filtering by status
            $table->index('status');
            
            // Composite index for most common query (approved reviews for a product)
            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['product_id', 'status']);
        });
    }
};
