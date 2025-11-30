<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Offer extends Model
{
    protected $fillable = [
        'offer_category_id',
        'title',
        'description',
        'image',
        'original_price',
        'offer_price',
        'discount_percentage',
        'discount_type',
        'buy_quantity',
        'get_quantity',
        'start_date',
        'end_date',
        'is_active',
        'is_featured',
        'is_limited_time',
        'sort_order'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'buy_quantity' => 'integer',
        'get_quantity' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_limited_time' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the category that owns the offer.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class, 'offer_category_id');
    }

    /**
     * Scope a query to only include active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include popular offers.
     */
    public function scopePopular($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include flash sale offers.
     */
    public function scopeFlashSale($query)
    {
        return $query->where('is_limited_time', true);
    }

    /**
     * Scope a query to only include current offers (within date range).
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where(function($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the discount amount.
     */
    public function getDiscountAmountAttribute()
    {
        return $this->original_price - $this->offer_price;
    }

    /**
     * Get the formatted discount percentage.
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === 'buy_x_get_y') {
            return "اشتر {$this->buy_quantity} واحصل على {$this->get_quantity}";
        }
        
        return $this->discount_percentage ? "{$this->discount_percentage}%" : null;
    }

    /**
     * Check if offer is currently active based on dates.
     */
    public function getIsCurrentlyActiveAttribute()
    {
        $now = Carbon::now();
        
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }
        
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }
        
        return $this->is_active;
    }
}
