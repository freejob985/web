<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['vendor', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()->orders()
            ->with(['vendor', 'items.product'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function confirmation($id)
    {
        $order = Auth::user()->orders()
            ->with(['vendor', 'items.product'])
            ->findOrFail($id);

        return view('orders.confirmation', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء هذا الطلب'
            ]);
        }

        $order->cancel($request->reason);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الطلب بنجاح'
        ]);
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $order = Auth::user()->orders()->findOrFail($id);

        if (!$order->canBeRated()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تقييم هذا الطلب'
            ]);
        }

        $order->addRating($request->rating, $request->review);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التقييم بنجاح'
        ]);
    }

    public function track($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['vendor', 'items.product'])
            ->first();

        if (!$order) {
            return view('orders.track-not-found', compact('orderNumber'));
        }

        // السماح بتتبع الطلب للمالك أو عرض معلومات محدودة للضيوف
        if (Auth::check() && $order->user_id === Auth::id()) {
            return view('orders.track', compact('order'));
        }

        // عرض معلومات محدودة للضيوف
        return view('orders.track-guest', compact('order'));
    }
}
