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
        Schema::table('offers', function (Blueprint $table) {
            // Add missing columns only if they don't exist
            if (!Schema::hasColumn('offers', 'is_popular')) {
                $table->boolean('is_popular')->default(false);
            }
            if (!Schema::hasColumn('offers', 'is_flash_sale')) {
                $table->boolean('is_flash_sale')->default(false);
            }
            if (!Schema::hasColumn('offers', 'new_price')) {
                $table->decimal('new_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('offers', 'discount_type')) {
                $table->string('discount_type')->default('percentage');
            }
            if (!Schema::hasColumn('offers', 'buy_quantity')) {
                $table->integer('buy_quantity')->nullable();
            }
            if (!Schema::hasColumn('offers', 'get_quantity')) {
                $table->integer('get_quantity')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn([
                'is_popular',
                'is_flash_sale', 
                'new_price',
                'discount_type',
                'buy_quantity',
                'get_quantity'
            ]);
        });
    }
};
