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
            // Add foreign key columns
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('governorate_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            
            // Add indexes
            $table->index(['category_id', 'is_active']);
            $table->index(['subcategory_id', 'is_active']);
            $table->index(['governorate_id', 'is_active']);
            $table->index(['city_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['city_id']);
            
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['subcategory_id', 'is_active']);
            $table->dropIndex(['governorate_id', 'is_active']);
            $table->dropIndex(['city_id', 'is_active']);
            
            $table->dropColumn(['category_id', 'subcategory_id', 'governorate_id', 'city_id']);
        });
    }
};
