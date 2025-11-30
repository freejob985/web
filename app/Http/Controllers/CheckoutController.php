<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product.vendor')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }

        $addresses = $user->addresses;
        $defaultAddress = $user->default_address;

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $savings = $cartItems->sum(function ($item) {
            if ($item->product->original_price && $item->product->original_price > $item->product->price) {
                return ($item->product->original_price - $item->product->price) * $item->quantity;
            }
            return 0;
        });

        $deliveryFee = $subtotal >= 10 ? 0 : 1;
        $tax = $subtotal * 0.15;
        $total = $subtotal + $deliveryFee + $tax;

        return view('checkout.index', compact(
            'cartItems', 
            'addresses', 
            'defaultAddress',
            'subtotal', 
            'savings', 
            'deliveryFee', 
            'tax', 
            'total'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_governorate' => 'required|string',
            'delivery_phone' => 'required|string',
            'delivery_notes' => 'nullable|string',
            'delivery_type' => 'required|in:immediate,fast,scheduled,free',
            'requested_delivery_at' => 'nullable|date',
            'payment_method' => 'required|in:cash,card,knet',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product.vendor')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة');
        }

        // التحقق من توفر المخزون
        foreach ($cartItems as $item) {
            if (!$item->product->isInStock($item->quantity)) {
                return back()->with('error', "المنتج {$item->product->name} غير متوفر بالكمية المطلوبة");
            }
        }

        DB::beginTransaction();

        try {
            // حساب الإجماليات
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $discountAmount = $cartItems->sum(function ($item) {
                if ($item->product->original_price && $item->product->original_price > $item->product->price) {
                    return ($item->product->original_price - $item->product->price) * $item->quantity;
                }
                return 0;
            });

            $deliveryFee = $subtotal >= 10 ? 0 : 1;
            $taxAmount = $subtotal * 0.15;
            $totalAmount = $subtotal + $deliveryFee + $taxAmount;

            // تجميع العناصر حسب المورد
            $itemsByVendor = $cartItems->groupBy('product.vendor_id');

            $orders = [];

            foreach ($itemsByVendor as $vendorId => $vendorItems) {
                $vendorSubtotal = $vendorItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                $vendorDeliveryFee = $vendorSubtotal >= 10 ? 0 : 1;
                $vendorTax = $vendorSubtotal * 0.15;
                $vendorTotal = $vendorSubtotal + $vendorDeliveryFee + $vendorTax;

                // إنشاء الطلب
                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId,
                    'subtotal' => $vendorSubtotal,
                    'delivery_fee' => $vendorDeliveryFee,
                    'tax_amount' => $vendorTax,
                    'discount_amount' => 0,
                    'total_amount' => $vendorTotal,
                    'delivery_address' => $request->delivery_address,
                    'delivery_city' => $request->delivery_city,
                    'delivery_governorate' => $request->delivery_governorate,
                    'delivery_phone' => $request->delivery_phone,
                    'delivery_notes' => $request->delivery_notes,
                    'delivery_type' => $request->delivery_type,
                    'requested_delivery_at' => $request->requested_delivery_at,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes
                ]);

                // إنشاء عناصر الطلب
                foreach ($vendorItems as $cartItem) {
                    OrderItem::createFromCartItem($order, $cartItem);
                }

                $orders[] = $order;
            }

            // مسح السلة
            $user->clearCart();

            // تحديث إحصائيات المستخدم
            $user->updateOrderStats($totalAmount);

            DB::commit();

            // إذا كان هناك طلب واحد فقط، توجه إلى صفحة التأكيد
            if (count($orders) === 1) {
                return redirect()->route('order.confirmation', $orders[0]->id)
                    ->with('success', 'تم تأكيد طلبك بنجاح');
            }

            // إذا كان هناك عدة طلبات، توجه إلى صفحة عامة
            return redirect()->route('orders.index')
                ->with('success', 'تم تأكيد طلباتك بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب');
        }
    }
}
