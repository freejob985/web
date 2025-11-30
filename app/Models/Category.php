<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Helpers\UrlHelper;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'image',
        'icon',
        'is_active',
        'sort_order'
    ];

    protected $appends = [
        'subcategories_count',
        'products_count'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the route key for the model.
     * استخدام slug بدلاً من id في routes
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // العلاقات
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    // Accessors & Mutators
    public function setNameArAttribute($value)
    {
        $this->attributes['name_ar'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getActiveSubcategoriesCountAttribute()
    {
        return $this->subcategories()->active()->count();
    }

    public function getActiveProductsCountAttribute()
    {
        return $this->products()->active()->count();
    }

    public function getSubcategoriesCountAttribute()
    {
        return $this->subcategories_count ?? 0;
    }

    public function getProductsCountAttribute()
    {
        return $this->products_count ?? 0;
    }

    public function getImageUrlAttribute()
    {
        return UrlHelper::storageUrl($this->image);
    }

    public function appendImageUrls()
    {
        $this->setAttribute('image_url', $this->image_url);
        return $this;
    }
}
