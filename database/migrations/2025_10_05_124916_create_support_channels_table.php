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
        Schema::create('support_channels', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar')->comment('عنوان القناة بالعربية');
            $table->string('title_en')->nullable()->comment('عنوان القناة بالإنجليزية');
            $table->text('description_ar')->comment('وصف القناة بالعربية');
            $table->text('description_en')->nullable()->comment('وصف القناة بالإنجليزية');
            $table->string('contact_info')->comment('معلومات التواصل (رقم هاتف أو إيميل)');
            $table->string('availability')->comment('ساعات العمل أو التوفر');
            $table->string('icon')->nullable()->comment('أيقونة القناة');
            $table->string('color')->default('#3B82F6')->comment('لون القناة');
            $table->integer('sort_order')->default(0)->comment('ترتيب القناة');
            $table->boolean('is_active')->default(true)->comment('حالة القناة');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_channels');
    }
};
