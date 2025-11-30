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
            // Drop indexes that depend on the columns we're removing
            $table->dropIndex(['category', 'is_active']);
        });
        
        Schema::table('products', function (Blueprint $table) {
            // Remove string fields that are now handled by relationships
            $table->dropColumn(['category', 'subcategory', 'brand']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back the string fields if needed to rollback
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
        });
        
        Schema::table('products', function (Blueprint $table) {
            // Recreate the index
            $table->index(['category', 'is_active']);
        });
    }
};