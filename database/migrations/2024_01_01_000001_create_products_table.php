<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 3);
            $table->decimal('original_price', 10, 3)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->unique();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            $table->string('origin')->default('الكويت');
            $table->decimal('weight', 8, 3)->nullable();
            $table->string('unit')->default('كيلو');
            $table->boolean('is_fresh')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->json('nutritional_info')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index(['vendor_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_fresh', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
