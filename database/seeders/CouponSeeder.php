<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'خصم ترحيبي 10%',
                'description' => 'خصم 10% على أول طلب لك',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'minimum_amount' => 20,
                'maximum_discount' => 50,
                'usage_limit_type' => 'limited',
                'usage_limit' => 1000,
                'applicable_type' => 'all_products',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3)
            ],
            [
                'code' => 'SAVE50',
                'name' => 'وفر 50 دينار',
                'description' => 'خصم ثابت 50 دينار على الطلبات أكثر من 100 دينار',
                'discount_type' => 'fixed',
                'discount_value' => 50,
                'minimum_amount' => 100,
                'usage_limit_type' => 'unlimited',
                'applicable_type' => 'all_products',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addYear()
            ],
            [
                'code' => 'FRESH20',
                'name' => 'خصم المنتجات الطازجة',
                'description' => 'خصم 20% على المنتجات الطازجة',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'minimum_amount' => 30,
                'maximum_discount' => 100,
                'usage_limit_type' => 'unlimited',
                'applicable_type' => 'specific_categories',
                'applicable_categories' => [1, 2], // Assuming categories 1 and 2 are fresh products
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6)
            ],
            [
                'code' => 'NEWUSER',
                'name' => 'خصم المستخدم الجديد',
                'description' => 'خصم 15% للمستخدمين الجدد',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'minimum_amount' => 25,
                'maximum_discount' => 30,
                'usage_limit_type' => 'limited',
                'usage_limit' => 1,
                'applicable_type' => 'all_products',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2)
            ],
            [
                'code' => 'BULK25',
                'name' => 'خصم الكمية',
                'description' => 'خصم 25% على الطلبات الكبيرة',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'minimum_amount' => 200,
                'maximum_discount' => 200,
                'usage_limit_type' => 'unlimited',
                'applicable_type' => 'all_products',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addYear()
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}