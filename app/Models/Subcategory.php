<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Subcategory extends Model
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
        'sort_order',
        'category_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors & Mutators
    public function setNameArAttribute($value)
    {
        $this->attributes['name_ar'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getActiveProductsCountAttribute()
    {
        return $this->products()->active()->count();
    }

    public function getImageUrlAttribute()
    {
        return \App\Helpers\UrlHelper::storageUrl($this->image);
    }

    public function appendImageUrls()
    {
        $this->setAttribute('image_url', $this->image_url);
        return $this;
    }
}
