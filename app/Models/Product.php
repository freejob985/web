<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'stock',
        'sku',
        'barcode',
        'image',
        'images',
        'origin',
        'weight',
        'unit',
        'is_fresh',
        'is_featured',
        'is_active',
        'status',
        'rating',
        'reviews_count',
        'sales_count',
        'vendor_id',
        'nutritional_info',
        'expiry_date',
        'category_id',
        'subcategory_id',
        'brand_id',
        'governorate_id',
        'city_id'
    ];

    protected $attributes = [
        'rating' => 0,
        'reviews_count' => 0,
        'sales_count' => 0,
        'status' => 'pending',
    ];

    protected $casts = [
        'images' => 'array',
        'nutritional_info' => 'array',
        'is_fresh' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:3',
        'original_price' => 'decimal:3',
        'weight' => 'decimal:3',
        'rating' => 'decimal:2',
        'expiry_date' => 'date'
    ];

    // العلاقات
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->approved();
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFresh($query)
    {
        return $query->where('is_fresh', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Accessors & Mutators
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format((float) $this->price, 3) . ' دينار';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? number_format((float) $this->original_price, 3) . ' دينار' : null;
    }

    // Methods
    public function updateStock($quantity, $operation = 'decrease')
    {
        if ($operation === 'decrease') {
            $this->decrement('stock', $quantity);
        } else {
            $this->increment('stock', $quantity);
        }
    }

    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
    }

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

    public function isInStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    public function getMainImage()
    {
        $mainImage = $this->image ?: (isset($this->images[0]) ? $this->images[0] : null);
        
        // If no image, return placeholder
        if (!$mainImage) {
            return 'products/placeholder.jpg';
        }
        
        return $mainImage;
    }

    public function getImages()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return array_map(function($image) {
            // If it's already a full URL, return as is
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            
            // If it's a relative path, convert to full URL
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function getImageUrlAttribute()
    {
        return \App\Helpers\UrlHelper::storageUrl($this->image);
    }

    public function getImagesUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return \App\Helpers\UrlHelper::storageUrls($this->images);
    }

    public function appendImageUrls()
    {
        $this->setAttribute('image_url', $this->image_url);
        $this->setAttribute('images_urls', $this->images_urls);
        return $this;
    }
}
