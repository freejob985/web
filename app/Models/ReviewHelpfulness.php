<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewHelpfulness extends Model
{
    use HasFactory;

    protected $table = 'review_helpfulness';

    protected $fillable = [
        'review_id',
        'review_type',
        'user_id',
        'is_helpful'
    ];

    protected $casts = [
        'is_helpful' => 'boolean'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeHelpful($query)
    {
        return $query->where('is_helpful', true);
    }

    public function scopeNotHelpful($query)
    {
        return $query->where('is_helpful', false);
    }

    public function scopeByReview($query, $reviewId, $reviewType)
    {
        return $query->where('review_id', $reviewId)->where('review_type', $reviewType);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i');
    }

    public function getHelpfulnessTextAttribute()
    {
        return $this->is_helpful ? 'مفيد' : 'غير مفيد';
    }
}