<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'read_at',
        'replied_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime'
    ];

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->whereIn('status', ['read', 'replied', 'closed']);
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'new' => 'جديد',
            'read' => 'مقروء',
            'replied' => 'تم الرد',
            'closed' => 'مغلق',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'new' => 'badge-danger',
            'read' => 'badge-warning',
            'replied' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-light'
        };
    }

    public function getIsUnreadAttribute()
    {
        return $this->status === 'new';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsReplied()
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now()
        ]);
    }

    public function markAsClosed()
    {
        $this->update(['status' => 'closed']);
    }

    public function addAdminNote($note)
    {
        $this->update(['admin_notes' => $note]);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($contactMessage) {
            // Create notification for new contact message
            \App\Models\Notification::createContactNotification($contactMessage);
        });
    }
}
