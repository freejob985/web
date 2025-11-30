<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::valid()->select([
            'id', 'code', 'name', 'description', 'discount_type', 
            'discount_value', 'minimum_amount', 'maximum_discount',
            'applicable_type', 'expires_at'
        ])->get();

        return response()->json([
            'success' => true,
            'coupons' => $coupons->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'description' => $coupon->description,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => (float) $coupon->discount_value,
                    'minimum_amount' => $coupon->minimum_amount ? (float) $coupon->minimum_amount : null,
                    'maximum_discount' => $coupon->maximum_discount ? (float) $coupon->maximum_discount : null,
                    'applicable_type' => $coupon->applicable_type,
                    'expires_at' => $coupon->expires_at?->toDateTimeString(),
                    'is_valid' => $coupon->is_valid,
                ];
            })
        ]);
    }

    public function validateCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $data['code'])->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صحيح'
            ], 404);
        }

        if (!$coupon->is_valid) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
            ], 422);
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($data['cart_items'] as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Check minimum amount
        if ($coupon->minimum_amount && $totalAmount < $coupon->minimum_amount) {
            return response()->json([
                'success' => false,
                'message' => "الحد الأدنى للطلب يجب أن يكون {$coupon->minimum_amount} دينار"
            ], 422);
        }

        // Check if coupon is applicable to cart items
        $isApplicable = true;
        if ($coupon->applicable_type === 'specific_products') {
            $productIds = collect($data['cart_items'])->pluck('product_id')->toArray();
            $applicableProducts = $coupon->applicable_products ?? [];
            $isApplicable = !empty(array_intersect($productIds, $applicableProducts));
        } elseif ($coupon->applicable_type === 'specific_categories') {
            // This would require loading products and checking their categories
            // For now, we'll assume it's applicable
            $isApplicable = true;
        }

        if (!$isApplicable) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير قابل للتطبيق على المنتجات المحددة'
            ], 422);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($totalAmount);

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
                'discount_amount' => (float) $discount,
                'minimum_amount' => $coupon->minimum_amount ? (float) $coupon->minimum_amount : null,
                'maximum_discount' => $coupon->maximum_discount ? (float) $coupon->maximum_discount : null,
            ],
            'calculation' => [
                'subtotal' => (float) $totalAmount,
                'discount' => (float) $discount,
                'total_after_discount' => (float) ($totalAmount - $discount)
            ]
        ]);
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'order_id' => 'required|exists:orders,id'
        ]);

        $coupon = Coupon::where('code', $data['code'])->first();

        if (!$coupon || !$coupon->is_valid) {
            return response()->json([
                'success' => false,
                'message' => 'كود الخصم غير صحيح أو غير صالح'
            ], 422);
        }

        // Increment usage
        $coupon->incrementUsage();

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق كود الخصم بنجاح'
        ]);
    }
}