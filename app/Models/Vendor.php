<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'email',
        'password',
        'phone',
        'business_name',
        'business_type',
        'commercial_record',
        'tax_number',
        'bank_account',
        'bank_name',
        'address',
        'city',
        'governorate',
        'postal_code',
        'business_category_id',
        'business_categories',
        'business_description',
        'description',
        'logo',
        'cover_image',
        'rating',
        'reviews_count',
        'orders_count',
        'total_sales',
        'status',
        'is_active',
        'is_featured',
        'is_fresh',
        'category',
        'business_hours',
        'delivery_areas',
        'delivery_fee',
        'free_delivery_threshold',
        'governorate_id',
        'city_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'rating' => 0,
        'reviews_count' => 0,
        'orders_count' => 0,
        'total_sales' => 0,
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'business_categories' => 'array',
        'business_hours' => 'array',
        'delivery_areas' => 'array',
        'rating' => 'decimal:2',
        'total_sales' => 'decimal:3',
        'delivery_fee' => 'decimal:3',
        'free_delivery_threshold' => 'decimal:3',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_fresh' => 'boolean'
    ];

    // العلاقات
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
    
    public function businessCategory(): BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(VendorSettings::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(VendorReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(VendorReview::class)->approved();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByGovernorate($query, $governorate)
    {
        return $query->where('governorate', $governorate);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating ?? 0, 1);
    }

    public function getFormattedTotalSalesAttribute()
    {
        return number_format($this->total_sales ?? 0, 3) . ' دينار';
    }

    public function getActiveProductsCountAttribute()
    {
        return $this->products()->active()->count();
    }

    public function getApprovedProductsCountAttribute()
    {
        return $this->products()->approved()->count();
    }

    public function getPendingOrdersCountAttribute()
    {
        return $this->orders()->where('status', 'pending')->count();
    }

    // Methods
    public function updateRating($newRating)
    {
        $totalRating = ($this->rating * $this->reviews_count) + $newRating;
        $this->increment('reviews_count');
        $this->update(['rating' => $totalRating / $this->reviews_count]);
    }

    public function recalculateRating($oldRating, $newRating)
    {
        $totalRating = ($this->rating * $this->reviews_count) - $oldRating + $newRating;
        $this->update(['rating' => $totalRating / $this->reviews_count]);
    }

    public function recalculateRatingAfterDelete($deletedRating)
    {
        if ($this->reviews_count <= 1) {
            $this->update(['rating' => 0, 'reviews_count' => 0]);
        } else {
            $totalRating = ($this->rating * $this->reviews_count) - $deletedRating;
            $this->decrement('reviews_count');
            $this->update(['rating' => $totalRating / $this->reviews_count]);
        }
    }

    public function incrementSales($amount)
    {
        $this->increment('total_sales', $amount);
        $this->increment('orders_count');
    }

    public function isOpen()
    {
        if (!$this->business_hours) {
            return true; // افتراضياً مفتوح 24/7
        }

        $currentDay = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');

        if (!isset($this->business_hours[$currentDay])) {
            return false;
        }

        $dayHours = $this->business_hours[$currentDay];
        if (!$dayHours['is_open']) {
            return false;
        }

        return $currentTime >= $dayHours['open_time'] && $currentTime <= $dayHours['close_time'];
    }

    public function deliversTo($governorate, $city = null)
    {
        if (!$this->delivery_areas) {
            return true; // افتراضياً يوصل لكل الكويت
        }

        foreach ($this->delivery_areas as $area) {
            if ($area['governorate'] === $governorate) {
                if (!$city || in_array($city, $area['cities'] ?? [])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getDeliveryFeeFor($orderTotal)
    {
        if ($orderTotal >= $this->free_delivery_threshold) {
            return 0;
        }
        return $this->delivery_fee;
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg';
    }
}
