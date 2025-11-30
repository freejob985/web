<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // نسخة من اسم المنتج وقت الطلب
            $table->string('product_sku'); // نسخة من SKU وقت الطلب
            $table->string('product_image')->nullable(); // نسخة من صورة المنتج
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 3); // السعر وقت الطلب
            $table->decimal('total_price', 10, 3); // المجموع للكمية
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
