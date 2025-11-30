<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'family_name',
        'email',
        'password',
        'phone',
        'birth_date',
        'avatar',
        'gender',
        'preferences',
        'email_notifications',
        'sms_notifications',
        'order_updates',
        'promotions',
        'newsletter',
        'total_spent',
        'orders_count',
        'last_order_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'preferences' => 'array',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'order_updates' => 'boolean',
        'promotions' => 'boolean',
        'newsletter' => 'boolean',
        'total_spent' => 'decimal:3',
        'last_order_at' => 'datetime'
    ];

    // العلاقات
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist');
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function vendorReviews(): HasMany
    {
        return $this->hasMany(VendorReview::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Accessors
    public function getDefaultAddressAttribute()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function getFormattedTotalSpentAttribute()
    {
        return number_format((float) $this->total_spent, 3) . ' دينار';
    }

    public function getCartTotalAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function getCartItemsCountAttribute()
    {
        return $this->cartItems->sum('quantity');
    }

    // Methods
    public function addToCart($productId, $quantity = 1, $notes = null)
    {
        $cartItem = $this->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            if ($notes) {
                $cartItem->update(['notes' => $notes]);
            }
        } else {
            $this->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'notes' => $notes
            ]);
        }
    }

    public function removeFromCart($productId)
    {
        $this->cartItems()->where('product_id', $productId)->delete();
    }

    public function clearCart()
    {
        $this->cartItems()->delete();
    }

    public function addToWishlist($productId)
    {
        if (!$this->wishlist()->where('product_id', $productId)->exists()) {
            $this->wishlist()->attach($productId);
        }
    }

    public function removeFromWishlist($productId)
    {
        $this->wishlist()->detach($productId);
    }

    public function isInWishlist($productId)
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    public function updateOrderStats($orderTotal)
    {
        $this->increment('total_spent', $orderTotal);
        $this->increment('orders_count');
        $this->update(['last_order_at' => now()]);
    }

    public function hasCompletedOrders()
    {
        return $this->orders()->where('status', 'delivered')->exists();
    }

    public function getCustomerLevel()
    {
        if ($this->total_spent >= 1000) {
            return 'VIP';
        } elseif ($this->total_spent >= 500) {
            return 'Gold';
        } elseif ($this->total_spent >= 100) {
            return 'Silver';
        }
        return 'Bronze';
    }
}
