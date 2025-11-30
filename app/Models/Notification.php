<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
        'icon',
        'color'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Static methods for creating notifications
    public static function createOrderNotification($order)
    {
        return self::create([
            'vendor_id' => $order->vendor_id,
            'user_id' => $order->user_id,
            'type' => 'order',
            'title' => 'طلب جديد',
            'message' => "تم إنشاء طلب جديد #{$order->id} من {$order->user->name}",
            'data' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'total' => $order->total,
                'status' => $order->status
            ],
            'icon' => 'fas fa-shopping-cart',
            'color' => 'success'
        ]);
    }

    public static function createContactNotification($contactMessage)
    {
        return self::create([
            'type' => 'contact',
            'title' => 'رسالة اتصال جديدة',
            'message' => "رسالة جديدة من {$contactMessage->name}: {$contactMessage->subject}",
            'data' => [
                'contact_message_id' => $contactMessage->id,
                'name' => $contactMessage->name,
                'email' => $contactMessage->email,
                'subject' => $contactMessage->subject
            ],
            'icon' => 'fas fa-envelope',
            'color' => 'info'
        ]);
    }

    // Vendor-specific notification methods
    public static function createVendorStockNotification($vendorId, $productName)
    {
        return self::create([
            'vendor_id' => $vendorId,
            'type' => 'stock',
            'title' => 'نفاد المخزون',
            'message' => "المنتج \"{$productName}\" أوشك على النفاد",
            'data' => [
                'product_name' => $productName,
                'notification_type' => 'stock_low'
            ],
            'icon' => 'fas fa-exclamation-triangle',
            'color' => 'warning'
        ]);
    }

    public static function createVendorReviewNotification($vendorId, $productName, $rating)
    {
        return self::create([
            'vendor_id' => $vendorId,
            'type' => 'review',
            'title' => 'تقييم جديد',
            'message' => "تقييم {$rating} نجوم لمنتج \"{$productName}\"",
            'data' => [
                'product_name' => $productName,
                'rating' => $rating,
                'notification_type' => 'product_review'
            ],
            'icon' => 'fas fa-star',
            'color' => 'info'
        ]);
    }

    public static function createVendorOrderStatusNotification($vendorId, $orderId, $status)
    {
        $statusLabels = [
            'pending' => 'في الانتظار',
            'processing' => 'قيد التحضير',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي'
        ];

        return self::create([
            'vendor_id' => $vendorId,
            'type' => 'order_status',
            'title' => 'تحديث حالة الطلب',
            'message' => "تم تحديث حالة الطلب #{$orderId} إلى: " . (isset($statusLabels[$status]) ? $statusLabels[$status] : $status),
            'data' => [
                'order_id' => $orderId,
                'status' => $status,
                'notification_type' => 'order_status_update'
            ],
            'icon' => 'fas fa-shopping-cart',
            'color' => 'info'
        ]);
    }
}