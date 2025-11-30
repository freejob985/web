<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Offer;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(): array
    {
        $cart = session()->get('cart', []);
        // Ensure cart is always an array
        if (!is_array($cart)) {
            $cart = [];
        }
        return $cart; // [product_id => ['quantity' => int, 'notes' => ?string]]
    }

    private function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
        session()->save();
    }
    

    
    private function serializeCart(array $cart)
    {
        if (empty($cart)) {
            return [
                'items' => [],
                'totals' => [
                    'subtotal' => 0, 'savings' => 0, 'delivery_fee' => 0, 'tax' => 0, 'total' => 0, 'currency' => 'KWD', 'count' => 0
                ],
            ];
        }
        
        // Separate products and offers
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
        $offers = Offer::with('category')->whereIn('id', $offerIds)->get()->keyBy('id');

        $items = [];
        $subtotal = 0.0;
        $savings = 0.0;

        foreach ($cart as $key => $row) {
            $qty = max(1, (int) ($row['quantity'] ?? 1));
            
            if (str_starts_with($key, 'offer_')) {
                // Handle offer
                $offerId = $row['offer_id'] ?? str_replace('offer_', '', $key);
                $offer = $offers->get($offerId);
                if (!$offer) {
                    continue;
                }
                
                    $price = (float) $offer->offer_price;
                $original = (float) $offer->original_price;
                $line = $price * $qty;
                $subtotal += $line;
                if ($original > $price) {
                    $savings += ($original - $price) * $qty;
                }

                $items[] = [
                    'id' => $offer->id,
                    'name' => $offer->title,
                    'price' => $price,
                    'original_price' => $original,
                    'image' => $offer->image ? asset('storage/' . $offer->image) : null,
                    'vendor' => null, // Offers don't have vendors
                    'quantity' => $qty,
                    'stock' => 999, // Assume unlimited stock for offers
                    'is_fresh' => false,
                    'type' => 'offer',
                    'category' => $offer->category ? ['id' => $offer->category->id, 'name' => $offer->category->name] : null,
                ];
            } else {
                // Handle product
                $product = $products->get($key);
                if (!$product) {
                    continue;
                }
                
                $price = (float) $product->price;
                $original = $product->original_price ? (float) $product->original_price : null;
                $line = $price * $qty;
                $subtotal += $line;
                if ($original && $original > $price) {
                    $savings += ($original - $price) * $qty;
                }

                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'original_price' => $original,
                    'image' => $product->getMainImage(),
                    'vendor' => $product->vendor ? [ 'id' => $product->vendor->id, 'name' => $product->vendor->name ] : null,
                    'quantity' => $qty,
                    'stock' => (int) $product->stock,
                    'is_fresh' => (bool) $product->is_fresh,
                    'type' => 'product',
                ];
            }
        }

        $deliveryFee = $subtotal >= 10 ? 0.0 : 1.0;
        $tax = $subtotal * 0.15;
        
        // Apply coupon discount if exists
        $appliedCoupon = session()->get('applied_coupon');
        $couponDiscount = 0;
        $couponData = null;
        
        if ($appliedCoupon) {
            $couponDiscount = (float) $appliedCoupon['calculation']['discount'];
            $couponData = $appliedCoupon['coupon_data'];
        }
        
        $total = $subtotal + $deliveryFee + $tax - $couponDiscount;
        $count = array_sum(array_map(function($r) { return (int) ($r['quantity'] ?? 1); }, $cart));

        return [
            'items' => $items,
            'totals' => [
                'subtotal' => round($subtotal, 3),
                'savings' => round($savings, 3),
                'delivery_fee' => round($deliveryFee, 3),
                'tax' => round($tax, 3),
                'coupon_discount' => round($couponDiscount, 3),
                'total' => round($total, 3),
                'currency' => 'KWD',
                'count' => (int) $count,
            ],
            'coupon' => $couponData,
        ];
    }

    public function index()
    {
        $cart = $this->getCart();
        return response()->json($this->serializeCart($cart));
    }

    public function count()
    {
        $cart = $this->getCart();
        $count = array_sum(array_map(fn ($r) => (int) ($r['quantity'] ?? 1), $cart));
        return response()->json(['count' => (int) $count]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'type' => 'nullable|string|in:product,offer',
        ]);

        $type = $data['type'] ?? 'product';
        $qty = (int) ($data['quantity'] ?? 1);

        if ($type === 'offer') {
            // Handle offer
            $offer = Offer::findOrFail($data['product_id']);
            if (!$offer->is_active) {
                return response()->json(['success' => false, 'message' => 'هذا العرض غير متاح حالياً'], 422);
            }
            
            // Check if offer is still valid
            if ($offer->end_date && now()->gt($offer->end_date)) {
                return response()->json(['success' => false, 'message' => 'انتهت صلاحية هذا العرض'], 422);
            }

            $cart = $this->getCart();
            $cartKey = 'offer_' . $offer->id;
            $row = $cart[$cartKey] ?? ['quantity' => 0, 'notes' => null, 'type' => 'offer', 'offer_id' => $offer->id];
            $row['quantity'] = (int) $row['quantity'] + $qty;
            if (!empty($data['notes'])) {
                $row['notes'] = $data['notes'];
            }
            $cart[$cartKey] = $row;
            $this->saveCart($cart);

            return response()->json(['success' => true] + $this->serializeCart($cart));
        } else {
            // Handle product
            $product = Product::findOrFail($data['product_id']);
            
            // Check if product is active and approved
            if (!$product->is_active) {
                return response()->json(['success' => false, 'message' => 'هذا المنتج غير متاح حالياً'], 422);
            }
            
            if (!$product->isInStock($qty)) {
                return response()->json(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون'], 422);
            }

            $cart = $this->getCart();
            $row = $cart[$product->id] ?? ['quantity' => 0, 'notes' => null, 'type' => 'product'];
            $row['quantity'] = (int) $row['quantity'] + $qty;
            if (!empty($data['notes'])) {
                $row['notes'] = $data['notes'];
            }
            $cart[$product->id] = $row;
            $this->saveCart($cart);

            return response()->json(['success' => true] + $this->serializeCart($cart));
        }
    }

    public function update(Request $request, int $productId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $product = Product::findOrFail($productId);
        if (!$product->isInStock($data['quantity'])) {
            return response()->json(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون'], 422);
        }
        $cart = $this->getCart();
        if (!isset($cart[$productId])) {
            return response()->json(['success' => false, 'message' => 'العنصر غير موجود في السلة'], 404);
        }
        $cart[$productId]['quantity'] = (int) $data['quantity'];
        $this->saveCart($cart);

        return response()->json(['success' => true] + $this->serializeCart($cart));
    }

    public function destroy(int $productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);
        return response()->json(['success' => true] + $this->serializeCart($cart));
    }

    public function clear()
    {
        $this->saveCart([]);
        return response()->json(['success' => true, 'items' => [], 'totals' => [
            'subtotal' => 0, 'savings' => 0, 'delivery_fee' => 0, 'tax' => 0, 'total' => 0, 'currency' => 'KWD', 'count' => 0
        ]]);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string'
        ]);

        $cart = $this->getCart();
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'السلة فارغة'
            ], 422);
        }

        // Prepare cart items for validation
        $cartItems = [];
        foreach ($cart as $key => $row) {
            if (str_starts_with($key, 'offer_')) {
                // Handle offers
                $offer = \App\Models\Offer::find($row['offer_id'] ?? str_replace('offer_', '', $key));
                if ($offer) {
                    $cartItems[] = [
                        'product_id' => $offer->id,
                        'quantity' => $row['quantity'],
                        'price' => $offer->price
                    ];
                }
            } else {
                // Handle products
                $product = \App\Models\Product::find($key);
                if ($product) {
                    $cartItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $row['quantity'],
                        'price' => $product->price
                    ];
                }
            }
        }

        // Validate coupon
        $couponRequest = new \Illuminate\Http\Request([
            'code' => $data['code'],
            'cart_items' => $cartItems
        ]);

        $couponController = new \App\Http\Controllers\Api\CouponController();
        $validationResponse = $couponController->validateCoupon($couponRequest);
        $validationData = $validationResponse->getData(true);

        if (!$validationData['success']) {
            return response()->json($validationData, $validationResponse->getStatusCode());
        }

        // Store coupon in session
        session()->put('applied_coupon', [
            'code' => $data['code'],
            'coupon_data' => $validationData['coupon'],
            'calculation' => $validationData['calculation']
        ]);
        session()->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق كود الخصم بنجاح',
            'coupon' => $validationData['coupon'],
            'calculation' => $validationData['calculation']
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        session()->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة كود الخصم'
        ]);
    }
}
