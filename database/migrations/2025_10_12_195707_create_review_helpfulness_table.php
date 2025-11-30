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
        if (!Schema::hasTable('review_helpfulness')) {
            Schema::create('review_helpfulness', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('review_id');
                $table->string('review_type'); // 'product' or 'vendor'
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->boolean('is_helpful')->default(true); // true for مفيد, false for غير مفيد
                $table->timestamps();

                // فهرس لمنع التقييم المتكرر من نفس المستخدم لنفس التقييم
                $table->unique(['review_id', 'review_type', 'user_id']);

                // فهارس للبحث والتصفية
                $table->index(['review_id', 'review_type']);
                $table->index(['user_id']);
                $table->index(['is_helpful']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_helpfulness');
    }
};