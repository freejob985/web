<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class DemoController extends Controller
{
    public function seed(Request $request)
    {
        $already = [
            'vendors' => Vendor::count(),
            'products' => Product::count(),
            'users' => User::count(),
            'orders' => Order::count(),
        ];

        if ($already['products'] >= 12 && $already['vendors'] >= 1 && $already['orders'] >= 1) {
            return response()->json(['success' => true, 'message' => 'Demo data already present', 'stats' => $already]);
        }

        // Vendors
        $v1 = Vendor::firstOrCreate(
            ['email' => 'vendor1@example.com'],
            [
                'name' => 'سوبرماركت إيليت ون',
                'password' => 'password',
                'phone' => '50000001',
                'address' => 'السالمية، شارع سالم المبارك',
                'city' => 'السالمية',
                'governorate' => 'حولي',
                'status' => 'approved',
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 120,
                'delivery_fee' => 1.000,
                'free_delivery_threshold' => 10.000,
            ]
        );
        $v2 = Vendor::firstOrCreate(
            ['email' => 'vendor2@example.com'],
            [
                'name' => 'مزارع فريش فارم',
                'password' => 'password',
                'phone' => '50000002',
                'address' => 'الجابرية، قطعة 3',
                'city' => 'الجابرية',
                'governorate' => 'حولي',
                'status' => 'approved',
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 80,
                'delivery_fee' => 0.500,
                'free_delivery_threshold' => 8.000,
            ]
        );

        // Helper to create product
        $makeProduct = function ($name, $category, $vendor, $price, $original = null, $image = null) {
            $sku = strtoupper(Str::slug($name)) . '-' . Str::random(4);
            return Product::create([
                'name' => $name,
                'description' => 'منتج تجريبي ضمن فئة ' . $category,
                'price' => $price,
                'original_price' => $original,
                'stock' => rand(20, 100),
                'sku' => $sku,
                'image' => $image,
                'images' => $image ? [$image] : null,
                'category' => $category,
                'brand' => 'Elite',
                'is_fresh' => $category === 'fruits-vegetables',
                'is_featured' => rand(0,1) === 1,
                'is_active' => true,
                'rating' => rand(40, 50) / 10,
                'reviews_count' => rand(5, 100),
                'sales_count' => rand(1, 300),
                'vendor_id' => $vendor->id,
            ]);
        };

        // Sample products
        $images = [
            'fruits-vegetables' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
            'dairy' => 'https://images.pexels.com/photos/8064204/pexels-photo-8064204.jpeg',
            'meat-poultry' => 'https://images.pexels.com/photos/19352815/pexels-photo-19352815.jpeg',
            'bakery' => 'https://images.pexels.com/photos/2680601/pexels-photo-2680601.jpeg',
            'grocery' => 'https://images.pexels.com/photos/8111375/pexels-photo-8111375.jpeg',
            'frozen' => 'https://images.pexels.com/photos/4846308/pexels-photo-4846308.jpeg',
        ];

        $products = [];
        $products[] = $makeProduct('تفاح أحمر', 'fruits-vegetables', $v2, 0.750, 0.900, $images['fruits-vegetables']);
        $products[] = $makeProduct('موز مستورد', 'fruits-vegetables', $v2, 0.650, 0.700, $images['fruits-vegetables']);
        $products[] = $makeProduct('خيار طازج', 'fruits-vegetables', $v2, 0.500, null, $images['fruits-vegetables']);

        $products[] = $makeProduct('حليب طازج 1لتر', 'dairy', $v1, 0.450, 0.500, $images['dairy']);
        $products[] = $makeProduct('لبنة كاملة الدسم', 'dairy', $v1, 0.900, 1.100, $images['dairy']);
        $products[] = $makeProduct('جبنة موزاريلا', 'dairy', $v1, 1.250, 1.500, $images['dairy']);

        $products[] = $makeProduct('صدر دجاج طازج', 'meat-poultry', $v1, 2.750, 2.950, $images['meat-poultry']);
        $products[] = $makeProduct('لحم بقر مفروم', 'meat-poultry', $v1, 3.200, 3.400, $images['meat-poultry']);

        $products[] = $makeProduct('خبز عربي', 'bakery', $v1, 0.200, null, $images['bakery']);
        $products[] = $makeProduct('كرواسون زبدة', 'bakery', $v1, 0.350, 0.400, $images['bakery']);

        $products[] = $makeProduct('أرز بسمتي 5كغ', 'grocery', $v1, 4.900, 5.300, $images['grocery']);
        $products[] = $makeProduct('زيت عباد الشمس 1لتر', 'grocery', $v1, 1.250, 1.450, $images['grocery']);

        $products[] = $makeProduct('خضار مجمدة مشكلة', 'frozen', $v1, 0.950, 1.100, $images['frozen']);
        $products[] = $makeProduct('آيس كريم فانيلا', 'frozen', $v1, 1.500, 1.700, $images['frozen']);

        // Demo user
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'عميل تجريبي',
                'password' => bcrypt('password'),
                'phone' => '50000003',
            ]
        );

        // Addresses
        UserAddress::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'المنزل',
        ], [
            'address' => 'السالمية، قطعة 5، شارع 10، منزل 12',
            'city' => 'السالمية',
            'governorate' => 'حولي',
            'phone' => '50000003',
            'is_default' => true,
        ]);
        UserAddress::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'العمل',
        ], [
            'address' => 'مدينة الكويت، شرق، برج 1، طابق 10',
            'city' => 'مدينة الكويت',
            'governorate' => 'العاصمة',
            'phone' => '50000003',
            'is_default' => false,
        ]);

        // Orders (create one per vendor)
        $recent = [];
        foreach ([$v1, $v2] as $vendor) {
            $vendorProducts = array_values(array_filter($products, fn($p) => $p->vendor_id === $vendor->id));
            if (count($vendorProducts) < 2) continue;
            $pA = $vendorProducts[0];
            $pB = $vendorProducts[1];
            $qtyA = 2;
            $qtyB = 1;
            $subtotal = ($pA->price * $qtyA) + ($pB->price * $qtyB);
            $delivery = $vendor->getDeliveryFeeFor($subtotal);
            $tax = round($subtotal * 0.15, 3);
            $total = $subtotal + $delivery + $tax;

            $order = Order::create([
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'delivery_fee' => $delivery,
                'tax_amount' => $tax,
                'discount_amount' => 0,
                'total_amount' => $total,
                'currency' => 'KWD',
                'delivery_address' => 'السالمية، قطعة 5، شارع 10، منزل 12',
                'delivery_city' => 'السالمية',
                'delivery_governorate' => 'حولي',
                'delivery_phone' => '50000003',
                'delivery_type' => 'immediate',
                'payment_method' => 'cash',
                'payment_status' => 'pending',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $pA->id,
                'product_name' => $pA->name,
                'product_sku' => $pA->sku,
                'product_image' => $pA->getMainImage(),
                'quantity' => $qtyA,
                'unit_price' => $pA->price,
                'total_price' => $pA->price * $qtyA,
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $pB->id,
                'product_name' => $pB->name,
                'product_sku' => $pB->sku,
                'product_image' => $pB->getMainImage(),
                'quantity' => $qtyB,
                'unit_price' => $pB->price,
                'total_price' => $pB->price * $qtyB,
            ]);

            $recent[] = $order->order_number;
        }

        if (!empty($recent)) {
            session()->put('recent_orders', $recent);
            session()->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Demo data seeded',
            'stats' => [
                'vendors' => Vendor::count(),
                'products' => Product::count(),
                'users' => User::count(),
                'orders' => Order::count(),
            ],
            'recent_orders' => $recent,
            'login' => ['email' => 'customer@example.com', 'password' => 'password']
        ]);
    }
}
