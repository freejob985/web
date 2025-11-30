<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'content',
        'image',
        'is_active',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'extra_data'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'extra_data' => 'array'
    ];

    // Sections constants
    const SECTION_HERO = 'hero';
    const SECTION_STORY = 'story';
    const SECTION_VALUES = 'values';
    const SECTION_STATISTICS = 'statistics';
    const SECTION_TEAM = 'team';
    const SECTION_MISSION = 'mission';

    // Get all sections
    public static function getSections()
    {
        return [
            self::SECTION_HERO => 'القسم الرئيسي',
            self::SECTION_STORY => 'قصتنا',
            self::SECTION_VALUES => 'قيمنا',
            self::SECTION_STATISTICS => 'أرقامنا تتحدث',
            self::SECTION_TEAM => 'فريقنا',
            self::SECTION_MISSION => 'رسالتنا'
        ];
    }

    // Scope for active sections
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific section
    public function scopeSection($query, $section)
    {
        return $query->where('section', $section);
    }

    // Scope ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
