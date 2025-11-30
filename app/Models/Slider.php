<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\UrlHelper;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_url',
        'image',
        'background_image',
        'badge',
        'gradient_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function getImageUrlAttribute()
    {
        return UrlHelper::storageUrl($this->image);
    }

    public function getBackgroundImageUrlAttribute()
    {
        return UrlHelper::storageUrl($this->background_image);
    }

    public function appendImageUrls()
    {
        $this->setAttribute('image_url', $this->image_url);
        $this->setAttribute('background_image_url', $this->background_image_url);
        return $this;
    }
}
