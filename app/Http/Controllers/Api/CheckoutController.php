<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private function getCart(): array
    {
        return session()->get('cart', []);
    }

    public function getCheckoutData()
    {
        $cart = $this->getCart();
        $subtotal = 0.0;
        $items = [];

        foreach ($cart as $key => $row) {
            $qty = max(1, (int) ($row['quantity'] ?? 1));
            
            if (str_starts_with($key, 'offer_')) {
                // معالجة العرض
                $offerId = $row['offer_id'] ?? str_replace('offer_', '', $key);
                $offer = \App\Models\Offer::find($offerId);
                if ($offer) {
                    $itemTotal = (float) $offer->offer_price * $qty;
                    $subtotal += $itemTotal;
                    
                    $items[] = [
                        'id' => $offer->id,
                        'name' => $offer->title,
                        'price' => (float) $offer->offer_price,
                        'original_price' => (float) $offer->original_price,
                        'quantity' => $qty,
                        'total' => $itemTotal,
                        'image' => $offer->image ? asset('storage/' . $offer->image) : null,
                        'vendor' => null, // العروض لا تحتاج مورد
                        'type' => 'offer'
                    ];
                }
            } else {
                // معالجة المنتج
                $product = \App\Models\Product::find($key);
                if ($product) {
                    $itemTotal = (float) $product->price * $qty;
                    $subtotal += $itemTotal;
                    
                    $items[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'original_price' => $product->original_price ? (float) $product->original_price : null,
                        'quantity' => $qty,
                        'total' => $itemTotal,
                        'image' => $product->getMainImage(),
                        'vendor' => $product->vendor ? [
                            'id' => $product->vendor->id,
                            'name' => $product->vendor->name
                        ] : null,
                        'type' => 'product'
                    ];
                }
            }
        }

        $deliveryFee = $subtotal >= 10 ? 0.0 : 1.0;
        $tax = $subtotal * 0.15;
        $total = $subtotal + $deliveryFee + $tax;

        return response()->json([
            'success' => true,
            'cart' => [
                'items' => $items,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax_amount' => $tax,
                'total' => $total
            ],
            'delivery_types' => [
                [
                    'value' => 'free',
                    'label' => 'توصيل مجاني',
                    'description' => 'للطلبات أكثر من 10 د.ك',
                    'fee' => 0
                ],
                [
                    'value' => 'immediate',
                    'label' => 'توصيل فوري',
                    'description' => 'خلال 30 دقيقة',
                    'fee' => 2
                ],
                [
                    'value' => 'fast',
                    'label' => 'توصيل سريع',
                    'description' => 'خلال ساعة',
                    'fee' => 1
                ],
                [
                    'value' => 'scheduled',
                    'label' => 'توصيل مجدول',
                    'description' => 'في الوقت المحدد',
                    'fee' => 0
                ]
            ],
            'payment_methods' => [
                [
                    'value' => 'cash',
                    'label' => 'الدفع عند الاستلام',
                    'description' => 'ادفع نقداً عند وصول الطلب'
                ],
                [
                    'value' => 'card',
                    'label' => 'بطاقة ائتمان',
                    'description' => 'ادفع بالبطاقة'
                ],
                [
                    'value' => 'knet',
                    'label' => 'كي نت',
                    'description' => 'ادفع عبر كي نت'
                ],
                [
                    'value' => 'wallet',
                    'label' => 'المحفظة الإلكترونية',
                    'description' => 'ادفع من رصيدك'
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('Checkout request received', [
            'request_data' => $request->all(),
            'session_id' => session()->getId()
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_governorate' => 'required|string',
            'delivery_notes' => 'nullable|string',
            'delivery_type' => 'required|in:immediate,fast,scheduled,free',
            'requested_delivery_at' => 'nullable|date',
            'payment_method' => 'required|in:cash,card,knet,wallet',
            'notes' => 'nullable|string'
        ]);

        $cart = $this->getCart();
        \Log::info('Cart contents', ['cart' => $cart]);
        
        if (empty($cart)) {
            \Log::warning('Empty cart during checkout', [
                'session_id' => session()->getId(),
                'session_data' => session()->all(),
                'cart_from_session' => session()->get('cart'),
                'request_data' => $data
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'السلة فارغة. تأكد من إضافة منتجات للسلة قبل الدفع.',
                'debug' => [
                    'session_id' => session()->getId(),
                    'cart_empty' => true
                ]
            ], 422);
        }

        // فصل المنتجات والعروض
        $productIds = [];
        $offerIds = [];
        
        foreach ($cart as $key => $row) {
            if (str_starts_with($key, 'offer_')) {
                $offerIds[] = $row['offer_id'] ?? str_replace('offer_', '', $key);
            } else {
                $productIds[] = $key;
            }
        }
        
        $products = Product::with('vendor')->whereIn('id', $productIds)->get()->keyBy('id');
        $offers = \App\Models\Offer::with('category')->whereIn('id', $offerIds)->get()->keyBy('id');
        
        // التحقق من توفر المنتجات والعروض
        foreach ($cart as $key => $row) {
            $qty = max(1, (int) ($row['quantity'] ?? 1));
            
            if (str_starts_with($key, 'offer_')) {
                // التحقق من العرض
                $offerId = $row['offer_id'] ?? str_replace('offer_', '', $key);
                $offer = $offers->get($offerId);
                if (!$offer) {
                    return response()->json(['success' => false, 'message' => 'أحد العروض غير متاح'], 422);
                }
                if (!$offer->is_active) {
                    return response()->json(['success' => false, 'message' => "العرض {$offer->title} غير متاح حالياً"], 422);
                }
                // التحقق من انتهاء صلاحية العرض
                if ($offer->end_date && now()->gt($offer->end_date)) {
                    return response()->json(['success' => false, 'message' => "انتهت صلاحية العرض {$offer->title}"], 422);
                }
            } else {
                // التحقق من المنتج
                $product = $products->get($key);
                if (!$product) {
                    return response()->json(['success' => false, 'message' => 'أحد المنتجات غير متاح'], 422);
                }
                if (!$product->isInStock($qty)) {
                    return response()->json(['success' => false, 'message' => "المنتج {$product->name} غير متوفر بالكمية المطلوبة"], 422);
                }
            }
        }

        DB::beginTransaction();
        try {
            // العثور على المستخدم أو إنشاؤه
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt(str()->random(16)),
                    'phone' => $data['phone'],
                ]
            );

            // حساب الإجماليات وتجميع العناصر حسب المورد
            $itemsByVendor = [];
            $subtotal = 0.0;
            
            foreach ($cart as $key => $row) {
                $qty = max(1, (int) ($row['quantity'] ?? 1));
                
                if (str_starts_with($key, 'offer_')) {
                    // معالجة العرض
                    $offerId = $row['offer_id'] ?? str_replace('offer_', '', $key);
                    $offer = $offers->get($offerId);
                    if ($offer) {
                        // العروض لا تحتاج مورد، نضعها في مجموعة خاصة
                        $itemsByVendor['offers'][] = ['offer' => $offer, 'quantity' => $qty, 'type' => 'offer'];
                        $subtotal += (float) $offer->offer_price * $qty;
                    }
                } else {
                    // معالجة المنتج
                    $product = $products->get($key);
                    if ($product) {
                        $itemsByVendor[$product->vendor_id][] = ['product' => $product, 'quantity' => $qty, 'type' => 'product'];
                        $subtotal += (float) $product->price * $qty;
                    }
                }
            }

            $orders = [];
            \Log::info('Creating orders for vendors', ['vendors' => array_keys($itemsByVendor)]);
            
            foreach ($itemsByVendor as $vendorId => $vendorItems) {
                $vendorSubtotal = 0.0;
                foreach ($vendorItems as $it) {
                    if ($it['type'] === 'offer') {
                        $vendorSubtotal += (float) $it['offer']->offer_price * $it['quantity'];
                    } else {
                        $vendorSubtotal += (float) $it['product']->price * $it['quantity'];
                    }
                }
                $vendorDeliveryFee = $vendorSubtotal >= 10 ? 0.0 : 1.0;
                $vendorTax = $vendorSubtotal * 0.15;
                $vendorTotal = $vendorSubtotal + $vendorDeliveryFee + $vendorTax;

                \Log::info('Creating order for vendor', [
                    'vendor_id' => $vendorId,
                    'subtotal' => $vendorSubtotal,
                    'total' => $vendorTotal
                ]);

                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId === 'offers' ? null : $vendorId,
                    'subtotal' => $vendorSubtotal,
                    'delivery_fee' => $vendorDeliveryFee,
                    'tax_amount' => $vendorTax,
                    'discount_amount' => 0,
                    'total_amount' => $vendorTotal,
                    'delivery_address' => $data['delivery_address'],
                    'delivery_city' => $data['delivery_city'],
                    'delivery_governorate' => $data['delivery_governorate'],
                    'delivery_phone' => $data['phone'],
                    'delivery_notes' => $data['delivery_notes'] ?? null,
                    'delivery_type' => $data['delivery_type'],
                    'requested_delivery_at' => $data['requested_delivery_at'] ?? null,
                    'payment_method' => $data['payment_method'],
                    'notes' => $data['notes'] ?? null,
                ]);

                \Log::info('Order created', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);

                foreach ($vendorItems as $it) {
                    if ($it['type'] === 'offer') {
                        // إنشاء عنصر طلب للعرض
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => null, // العروض لا تحتاج معرف منتج
                            'offer_id' => $it['offer']->id,
                            'product_name' => $it['offer']->title,
                            'product_sku' => 'OFFER-' . $it['offer']->id,
                            'product_image' => $it['offer']->image ? asset('storage/' . $it['offer']->image) : null,
                            'quantity' => $it['quantity'],
                            'unit_price' => $it['offer']->offer_price,
                            'total_price' => $it['offer']->offer_price * $it['quantity'],
                            'name' => $it['offer']->title,
                            'price' => $it['offer']->offer_price,
                            'original_price' => $it['offer']->original_price,
                            'total' => $it['offer']->offer_price * $it['quantity'],
                            'type' => 'offer',
                            'notes' => $cart['offer_' . $it['offer']->id]['notes'] ?? null,
                        ]);
                    } else {
                        // إنشاء عنصر طلب للمنتج
                        OrderItem::createFromCartItem($order, (object) [
                            'product' => $it['product'],
                            'quantity' => $it['quantity'],
                            'notes' => $cart[$it['product']->id]['notes'] ?? null,
                        ]);
                    }
                }

                $orders[] = $order;
            }

            \Log::info('All orders created', [
                'orders_count' => count($orders),
                'order_numbers' => array_map(fn($o) => $o->order_number, $orders)
            ]);

            // مسح السلة وتحديث إحصائيات المستخدم
            session()->forget('cart');
            // تخزين أرقام الطلبات الأخيرة في الجلسة
            $recent = session()->get('recent_orders', []);
            foreach ($orders as $o) { $recent[] = $o->order_number; }
            session()->put('recent_orders', array_values(array_unique($recent)));

            $totalAmount = array_reduce($orders, fn ($c, $o) => $c + (float) $o->total_amount, 0.0);
            $user->updateOrderStats($totalAmount);

            DB::commit();

            if (empty($orders)) {
                \Log::error('No orders created during checkout', [
                    'user_id' => $user->id,
                    'cart' => $cart,
                    'items_by_vendor' => $itemsByVendor
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'فشل في إنشاء الطلبات'
                ], 500);
            }

            $response = [
                'success' => true,
                'orders' => array_map(fn ($o) => [
                    'id' => $o->id,
                    'order_number' => $o->order_number,
                    'vendor_id' => $o->vendor_id,
                    'total' => (float) $o->total_amount,
                ], $orders),
            ];

            \Log::info('Checkout successful', [
                'orders_count' => count($orders),
                'order_numbers' => array_column($response['orders'], 'order_number'),
                'user_id' => $user->id,
                'response' => $response
            ]);

            return response()->json($response);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => $data
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()
            ], 500);
        }
    }
}
