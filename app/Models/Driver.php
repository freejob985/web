<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'license_number',
        'vehicle_type',
        'vehicle_number',
        'status',
        'fcm_token',
    ];

    /**
     * العلاقة مع الطلبات
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * تحديث Firebase token للسائق
     */
    public function updateFcmToken($token)
    {
        $this->update(['fcm_token' => $token]);
    }

    /**
     * التحقق من توفر السائق
     */
    public function isAvailable()
    {
        return $this->status === 'active';
    }

    /**
     * تحديث حالة السائق
     */
    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
    }
}
