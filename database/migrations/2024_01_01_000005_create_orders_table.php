<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 'ready', 'shipped', 'delivered', 'cancelled'
            ])->default('pending');
            $table->decimal('subtotal', 10, 3);
            $table->decimal('delivery_fee', 8, 3)->default(0);
            $table->decimal('tax_amount', 8, 3)->default(0);
            $table->decimal('discount_amount', 8, 3)->default(0);
            $table->decimal('total_amount', 10, 3);
            $table->string('currency', 3)->default('KWD');
            
            // عنوان التوصيل
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_governorate');
            $table->string('delivery_phone');
            $table->text('delivery_notes')->nullable();
            
            // توقيت التوصيل
            $table->enum('delivery_type', ['immediate', 'fast', 'scheduled', 'free'])->default('fast');
            $table->timestamp('requested_delivery_at')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // الدفع
            $table->enum('payment_method', ['cash', 'card', 'knet', 'wallet'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // ملاحظات وتتبع
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['vendor_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
