<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'contact_info',
        'availability',
        'icon',
        'color',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope for active channels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the title based on locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?? $this->title_ar);
    }

    /**
     * Get the description based on locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : ($this->description_en ?? $this->description_ar);
    }
}
