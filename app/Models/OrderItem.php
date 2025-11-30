<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'offer_id',
        'product_name',
        'product_sku',
        'product_image',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
        'name',
        'price',
        'original_price',
        'total',
        'type'
    ];

    protected $casts = [
        'unit_price' => 'decimal:3',
        'total_price' => 'decimal:3'
    ];

    // العلاقات
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    // Accessors
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 3) . ' دينار';
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 3) . ' دينار';
    }

    // Methods
    public static function createFromCartItem($order, $cartItem)
    {
        $product = $cartItem->product;
        
        return static::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'product_image' => $product->getMainImage(),
            'quantity' => $cartItem->quantity,
            'unit_price' => $product->price,
            'total_price' => $product->price * $cartItem->quantity,
            'notes' => $cartItem->notes
        ]);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($orderItem) {
            // تقليل المخزون عند إنشاء عنصر الطلب (فقط للمنتجات العادية)
            if ($orderItem->product_id && $orderItem->product) {
                $orderItem->product->updateStock($orderItem->quantity, 'decrease');
                
                // زيادة عداد المبيعات
                $orderItem->product->incrementSales($orderItem->quantity);
            }
            // العروض لا تحتاج تقليل مخزون أو زيادة مبيعات
        });
    }
}
