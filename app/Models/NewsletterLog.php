<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'newsletter_id',
        'status',
        'error_message',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    // Relations
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(NewsletterCampaign::class, 'campaign_id');
    }

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    // Scopes
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeBounced($query)
    {
        return $query->where('status', 'bounced');
    }
}