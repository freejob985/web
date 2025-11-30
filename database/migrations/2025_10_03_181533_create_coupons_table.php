<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('discount_value', 10, 3);
            $table->decimal('minimum_amount', 10, 3)->nullable();
            $table->decimal('maximum_discount', 10, 3)->nullable();
            $table->enum('usage_limit_type', ['unlimited', 'limited'])->default('unlimited');
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->enum('applicable_type', ['all_products', 'specific_products', 'specific_categories'])->default('all_products');
            $table->json('applicable_products')->nullable();
            $table->json('applicable_categories')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};