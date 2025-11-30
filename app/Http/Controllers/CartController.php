<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.vendor')->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $savings = $cartItems->sum(function ($item) {
            if ($item->product->original_price && $item->product->original_price > $item->product->price) {
                return ($item->product->original_price - $item->product->price) * $item->quantity;
            }
            return 0;
        });

        $deliveryFee = $subtotal >= 10 ? 0 : 1; // 1 دينار رسوم توصيل
        $tax = $subtotal * 0.15; // 15% ضريبة
        $total = $subtotal + $deliveryFee + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'savings', 'deliveryFee', 'tax', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isInStock($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة في المخزون'
            ]);
        }

        try {
            Auth::user()->addToCart($request->product_id, $request->quantity, $request->notes);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج إلى السلة بنجاح',
                'cart_count' => Auth::user()->cart_items_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المنتج'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Auth::user()->cartItems()->findOrFail($id);

        try {
            $cartItem->updateQuantity($request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكمية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function remove($id)
    {
        $cartItem = Auth::user()->cartItems()->findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة',
            'cart_count' => Auth::user()->cart_items_count
        ]);
    }

    public function clear()
    {
        Auth::user()->clearCart();

        return response()->json([
            'success' => true,
            'message' => 'تم مسح السلة بالكامل'
        ]);
    }

    public function count()
    {
        return response()->json([
            'count' => Auth::user()->cart_items_count
        ]);
    }
}
