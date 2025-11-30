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
        Schema::create('vendor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned()->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->json('images')->nullable(); // صور إضافية للتعليق
            $table->timestamps();
            
            // فهرس لمنع التقييم المتكرر من نفس المستخدم لنفس المورد
            $table->unique(['vendor_id', 'user_id']);
            
            // فهارس للبحث والتصفية
            $table->index(['vendor_id', 'is_approved']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_reviews');
    }
};
