<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'vendor_id',
        'rating',
        'comment',
        'is_approved',
        'is_anonymous',
        'images'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_anonymous' => 'boolean',
        'images' => 'array',
        'rating' => 'integer'
    ];

    // العلاقات
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function helpfulness(): HasMany
    {
        return $this->hasMany(ReviewHelpfulness::class, 'review_id')
            ->where('review_type', 'product');
    }

    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpfulness::class, 'review_id')
            ->where('review_type', 'product')
            ->where('is_helpful', true);
    }

    public function notHelpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpfulness::class, 'review_id')
            ->where('review_type', 'product')
            ->where('is_helpful', false);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i');
    }

    public function getDisplayNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'مستخدم مجهول';
        }
        return $this->user->name ?? 'مستخدم محذوف';
    }

    public function getImagesUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return array_map(function($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            // استخدام URL مطلق للتأكد من صحة الرابط
            $baseUrl = config('app.url', 'http://localhost:8000');
            return rtrim($baseUrl, '/') . '/storage/' . ltrim($image, '/');
        }, $this->images);
    }
}
