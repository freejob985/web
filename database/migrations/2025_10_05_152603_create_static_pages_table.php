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
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // مثل 'terms' أو 'privacy'
            $table->string('title'); // عنوان الصفحة
            $table->text('content'); // محتوى الصفحة
            $table->text('meta_description')->nullable(); // وصف meta
            $table->string('meta_keywords')->nullable(); // كلمات مفتاحية
            $table->boolean('is_active')->default(true); // هل الصفحة نشطة
            $table->boolean('is_fixed')->default(false); // هل الصفحة ثابتة (لا تقبل التعديل)
            $table->integer('sort_order')->default(0); // ترتيب الصفحة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_pages');
    }
};
