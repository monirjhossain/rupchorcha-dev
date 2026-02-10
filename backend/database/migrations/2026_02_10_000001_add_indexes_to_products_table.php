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
        Schema::table('products', function (Blueprint $table) {
            // Index for search queries
            $table->index('name');
            
            // Indexes for filtering
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('status');
            $table->index('featured');
            
            // Composite index for common query combinations
            $table->index(['status', 'featured']);
            $table->index(['category_id', 'status']);
            $table->index(['brand_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['status', 'featured']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex(['brand_id', 'status']);
        });
    }
};
