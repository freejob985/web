<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'notes'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getTotalPriceAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 3) . ' دينار';
    }

    // Methods
    public function updateQuantity($quantity)
    {
        if ($quantity <= 0) {
            $this->delete();
            return;
        }

        // التحقق من توفر المخزون
        if (!$this->product->isInStock($quantity)) {
            throw new \Exception('الكمية المطلوبة غير متوفرة ��ي المخزون');
        }

        $this->update(['quantity' => $quantity]);
    }

    public function incrementQuantity($amount = 1)
    {
        $newQuantity = $this->quantity + $amount;
        $this->updateQuantity($newQuantity);
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = $this->quantity - $amount;
        $this->updateQuantity($newQuantity);
    }
}
