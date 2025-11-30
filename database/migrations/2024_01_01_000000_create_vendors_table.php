<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('commercial_record')->unique()->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('governorate');
            $table->string('postal_code')->nullable();
            $table->json('business_categories')->nullable();
            $table->text('business_description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->integer('orders_count')->default(0);
            $table->decimal('total_sales', 12, 3)->default(0);
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(false);
            $table->json('business_hours')->nullable();
            $table->json('delivery_areas')->nullable();
            $table->decimal('delivery_fee', 8, 3)->default(0);
            $table->decimal('free_delivery_threshold', 8, 3)->default(10);
            $table->rememberToken();
            $table->timestamps();
            
            $table->index(['status', 'is_active']);
            $table->index(['city', 'governorate']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
