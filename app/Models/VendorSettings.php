<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorSettings extends Model
{
    protected $fillable = [
        'vendor_id',
        'store_name',
        'store_description',
        'store_logo',
        'store_cover_image',
        'store_phone',
        'store_email',
        'store_address',
        'store_city',
        'store_governorate',
        'business_license',
        'tax_number',
        'commercial_record',
        'business_category',
        'delivery_enabled',
        'delivery_fee',
        'free_delivery_threshold',
        'delivery_time_min',
        'delivery_time_max',
        'delivery_areas',
        'cash_on_delivery',
        'card_payment',
        'knet_payment',
        'wallet_payment',
        'email_notifications',
        'sms_notifications',
        'order_notifications',
        'stock_notifications',
        'promotion_notifications',
        'return_policy',
        'shipping_policy',
        'privacy_policy',
        'return_period_days',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'website_url',
        'working_hours',
        'is_24_hours',
        'auto_accept_orders',
        'require_order_confirmation',
        'max_order_amount',
        'min_order_amount',
        'is_active',
    ];

    protected $casts = [
        'delivery_enabled' => 'boolean',
        'delivery_fee' => 'decimal:2',
        'free_delivery_threshold' => 'decimal:2',
        'delivery_time_min' => 'integer',
        'delivery_time_max' => 'integer',
        'delivery_areas' => 'array',
        'cash_on_delivery' => 'boolean',
        'card_payment' => 'boolean',
        'knet_payment' => 'boolean',
        'wallet_payment' => 'boolean',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'order_notifications' => 'boolean',
        'stock_notifications' => 'boolean',
        'promotion_notifications' => 'boolean',
        'return_period_days' => 'integer',
        'working_hours' => 'array',
        'is_24_hours' => 'boolean',
        'auto_accept_orders' => 'boolean',
        'require_order_confirmation' => 'boolean',
        'max_order_amount' => 'integer',
        'min_order_amount' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the vendor that owns the settings.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
