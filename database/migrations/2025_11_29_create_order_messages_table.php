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
        Schema::create('order_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('sender_type', ['user', 'driver', 'admin']); // نوع المرسل
            $table->foreignId('sender_id')->nullable(); // معرف المرسل
            $table->text('message'); // نص الرسالة
            $table->string('attachment')->nullable(); // مرفق (صورة، ملف، إلخ)
            $table->boolean('is_read')->default(false); // هل تم قراءة الرسالة
            $table->timestamp('read_at')->nullable(); // وقت القراءة
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index(['sender_type', 'sender_id']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_messages');
    }
};
