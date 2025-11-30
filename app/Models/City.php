<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'is_active',
        'sort_order',
        'governorate_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
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

    public function scopeByGovernorate($query, $governorateId)
    {
        return $query->where('governorate_id', $governorateId);
    }
}
