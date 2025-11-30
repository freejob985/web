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
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('offer_category_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->decimal('original_price', 10, 2);
                $table->decimal('new_price', 10, 2);
                $table->integer('discount_percentage')->nullable();
                $table->string('discount_type')->default('percentage'); // percentage, fixed, buy_x_get_y
                $table->integer('buy_quantity')->nullable(); // for buy x get y offers
                $table->integer('get_quantity')->nullable(); // for buy x get y offers
                $table->datetime('start_date')->nullable();
                $table->datetime('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_popular')->default(false);
                $table->boolean('is_flash_sale')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
