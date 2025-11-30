<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_fixed',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_fixed' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope للحصول على الصفحات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للحصول على الصفحات الثابتة
     */
    public function scopeFixed($query)
    {
        return $query->where('is_fixed', true);
    }

    /**
     * Scope للحصول على الصفحات القابلة للتعديل
     */
    public function scopeEditable($query)
    {
        return $query->where('is_fixed', false);
    }

    /**
     * الحصول على صفحة بالـ slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->active()->first();
    }

    /**
     * الحصول على الصفحات مرتبة
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
