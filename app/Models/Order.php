<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'vendor_id',
        'driver_id',
        'status',
        'subtotal',
        'delivery_fee',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'delivery_address',
        'delivery_city',
        'delivery_governorate',
        'delivery_phone',
        'delivery_notes',
        'delivery_type',
        'requested_delivery_at',
        'estimated_delivery_at',
        'delivered_at',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'rating',
        'review',
        'reviewed_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'delivery_fee' => 'decimal:3',
        'tax_amount' => 'decimal:3',
        'discount_amount' => 'decimal:3',
        'total_amount' => 'decimal:3',
        'requested_delivery_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'reviewed_at' => 'datetime'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class);
    }


    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['delivered', 'cancelled']);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التحضير',
            'ready' => 'جاهز',
            'shipped' => 'قيد التوصيل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل',
            'refunded' => 'مسترد',
            default => $this->payment_status
        };
    }

    public function getDeliveryTypeLabelAttribute()
    {
        return match($this->delivery_type) {
            'immediate' => 'فوري (30-60 دقيقة)',
            'fast' => 'سريع (1-2 ساعة)',
            'scheduled' => 'مجدول',
            'free' => 'مجاني (2-4 ساعات)',
            default => $this->delivery_type
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 3) . ' دينار';
    }

    public function getItemsCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Methods
    public function generateOrderNumber()
    {
        $prefix = 'ORD-' . now()->format('Y');
        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, strlen($prefix . '-')));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function updateStatus($status, $notes = null)
    {
        $this->update([
            'status' => $status,
            'notes' => $notes
        ]);

        // تحديث أوقات محدد�� حسب الحالة
        match($status) {
            'delivered' => $this->update(['delivered_at' => now()]),
            'cancelled' => $this->update(['cancelled_at' => now()]),
            default => null
        };
    }

    public function markAsPaid($paymentReference = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_reference' => $paymentReference,
            'paid_at' => now()
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now()
        ]);

        // إرجاع المخزون
        foreach ($this->items as $item) {
            $item->product->updateStock($item->quantity, 'increase');
        }
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeRated()
    {
        return $this->status === 'delivered' && !$this->rating;
    }

    public function addRating($rating, $review = null)
    {
        $this->update([
            'rating' => $rating,
            'review' => $review,
            'reviewed_at' => now()
        ]);

        // تحديث تقييم المتجر
        $this->vendor->updateRating($rating);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = $order->generateOrderNumber();
            }
        });

        static::created(function ($order) {
            // Create notification for new order
            \App\Models\Notification::createOrderNotification($order);
        });
    }
}
