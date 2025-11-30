<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit_type',
        'usage_limit',
        'used_count',
        'applicable_type',
        'applicable_products',
        'applicable_categories',
        'status',
        'starts_at',
        'expires_at'
    ];

    protected $casts = [
        'discount_value' => 'decimal:3',
        'minimum_amount' => 'decimal:3',
        'maximum_discount' => 'decimal:3',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->where('usage_limit_type', 'unlimited')
                  ->orWhereRaw('used_count < usage_limit');
            });
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
               ($this->starts_at === null || $this->starts_at <= now()) &&
               ($this->expires_at === null || $this->expires_at >= now());
    }

    public function getIsValidAttribute()
    {
        return $this->is_active &&
               ($this->usage_limit_type === 'unlimited' || $this->used_count < $this->usage_limit);
    }

    public function getRemainingUsesAttribute()
    {
        if ($this->usage_limit_type === 'unlimited') {
            return null;
        }
        return max(0, $this->usage_limit - $this->used_count);
    }

    // Methods
    public function calculateDiscount($amount)
    {
        if (!$this->is_valid || $amount < $this->minimum_amount) {
            return 0;
        }

        $discount = 0;
        if ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;
        } else {
            $discount = ($amount * $this->discount_value) / 100;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return min($discount, $amount);
    }

    public function isApplicableToProduct($productId)
    {
        if ($this->applicable_type === 'all_products') {
            return true;
        }

        if ($this->applicable_type === 'specific_products') {
            return in_array($productId, $this->applicable_products ?? []);
        }

        return false;
    }

    public function isApplicableToCategory($categoryId)
    {
        if ($this->applicable_type === 'all_products') {
            return true;
        }

        if ($this->applicable_type === 'specific_categories') {
            return in_array($categoryId, $this->applicable_categories ?? []);
        }

        return false;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    public function decrementUsage()
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
    }

    public function canBeUsed()
    {
        return $this->is_valid;
    }
}