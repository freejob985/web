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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable(); // اسم العرض أو المنتج
            $table->decimal('price', 10, 3)->nullable(); // السعر
            $table->decimal('original_price', 10, 3)->nullable(); // السعر الأصلي
            $table->decimal('total', 10, 3)->nullable(); // المجموع
            $table->string('type')->default('product'); // نوع العنصر: product أو offer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['offer_id', 'name', 'price', 'original_price', 'total', 'type']);
        });
    }
};
