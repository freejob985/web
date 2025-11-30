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
        Schema::create('vendor_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            
            // Store Settings
            $table->string('store_name')->nullable();
            $table->text('store_description')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_cover_image')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->text('store_address')->nullable();
            $table->string('store_city')->nullable();
            $table->string('store_governorate')->nullable();
            
            // Business Information
            $table->string('business_license')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_record')->nullable();
            $table->string('business_category')->nullable();
            
            // Delivery Settings
            $table->boolean('delivery_enabled')->default(true);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('free_delivery_threshold', 8, 2)->nullable();
            $table->integer('delivery_time_min')->default(30);
            $table->integer('delivery_time_max')->default(120);
            $table->json('delivery_areas')->nullable();
            
            // Payment Settings
            $table->boolean('cash_on_delivery')->default(true);
            $table->boolean('card_payment')->default(false);
            $table->boolean('knet_payment')->default(false);
            $table->boolean('wallet_payment')->default(false);
            
            // Notification Settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(true);
            $table->boolean('order_notifications')->default(true);
            $table->boolean('stock_notifications')->default(true);
            $table->boolean('promotion_notifications')->default(true);
            
            // Store Policies
            $table->text('return_policy')->nullable();
            $table->text('shipping_policy')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->integer('return_period_days')->default(7);
            
            // Social Media
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('website_url')->nullable();
            
            // Working Hours
            $table->json('working_hours')->nullable();
            $table->boolean('is_24_hours')->default(false);
            
            // Additional Settings
            $table->boolean('auto_accept_orders')->default(false);
            $table->boolean('require_order_confirmation')->default(true);
            $table->integer('max_order_amount')->nullable();
            $table->integer('min_order_amount')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_settings');
    }
};
