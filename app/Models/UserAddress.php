<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'address',
        'city',
        'governorate',
        'block',
        'street',
        'building',
        'floor',
        'apartment',
        'phone',
        'notes',
        'is_default',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Methods
    public function setAsDefault()
    {
        // إزالة الافتراضي من العناوين الأخرى
        $this->user->addresses()->where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // تعيين هذا العنوان كافتراضي
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->block ? "قطعة {$this->block}" : null,
            $this->street ? "شارع {$this->street}" : null,
            $this->building ? "مبنى {$this->building}" : null,
            $this->floor ? "طابق {$this->floor}" : null,
            $this->apartment ? "شقة {$this->apartment}" : null,
            $this->city,
            $this->governorate
        ]);

        return implode('، ', $parts);
    }

    // Boot method لضمان عنوان افتراضي واحد فقط
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->is_default) {
                static::where('user_id', $address->user_id)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($address) {
            if ($address->is_default && $address->isDirty('is_default')) {
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
