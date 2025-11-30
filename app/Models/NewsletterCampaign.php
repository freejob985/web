<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'html_content',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
        'scheduled_at',
        'sent_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime'
    ];

    // Relations
    public function logs(): HasMany
    {
        return $this->hasMany(NewsletterLog::class, 'campaign_id');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSending($query)
    {
        return $query->where('status', 'sending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors
    public function getProgressPercentageAttribute()
    {
        if ($this->total_recipients == 0) {
            return 0;
        }
        return round(($this->sent_count / $this->total_recipients) * 100, 2);
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'sent' || $this->status === 'failed';
    }

    public function getIsSendingAttribute()
    {
        return $this->status === 'sending';
    }

    // Methods
    public function startSending()
    {
        $this->update([
            'status' => 'sending',
            'total_recipients' => Newsletter::active()->count()
        ]);
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed'
        ]);
    }

    public function incrementSent()
    {
        $this->increment('sent_count');
    }

    public function incrementFailed()
    {
        $this->increment('failed_count');
    }
}