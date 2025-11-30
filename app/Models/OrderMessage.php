<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * العلاقة مع الطلب
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * الحصول على المرسل (User أو Driver)
     */
    public function sender()
    {
        if ($this->sender_type === 'user') {
            return $this->belongsTo(User::class, 'sender_id');
        } elseif ($this->sender_type === 'driver') {
            return $this->belongsTo(Driver::class, 'sender_id');
        }
        
        return null;
    }

    /**
     * تحديد الرسالة كمقروءة
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * تحديد المستلم بناءً على نوع المرسل
     */
    public function getRecipient()
    {
        $order = $this->order;

        if ($this->sender_type === 'user') {
            // إذا أرسل المستخدم، المستلم هو السائق
            return [
                'type' => 'driver',
                'id' => $order->driver_id,
                'model' => $order->driver,
            ];
        } elseif ($this->sender_type === 'driver') {
            // إذا أرسل السائق، المستلم هو المستخدم
            return [
                'type' => 'user',
                'id' => $order->user_id,
                'model' => $order->user,
            ];
        }

        return null;
    }
}
