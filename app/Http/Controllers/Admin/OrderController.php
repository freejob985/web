<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,preparing,ready,shipped,delivered,cancelled'
        ]);

        $q = Order::with(['user','vendor'])->latest();
        $status = $validated['status'] ?? null;
        if ($status) {
            $q->where('status', $status);
        }
        $orders = $q->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function pending(Request $request)
    {
        $orders = Order::with(['user','vendor'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'shipped'])
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.pending', compact('orders'));
    }

    public function completed(Request $request)
    {
        $orders = Order::with(['user','vendor'])
            ->whereIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.completed', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user','vendor','items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,shipped,delivered,cancelled',
            'notes' => 'nullable|string'
        ]);
        $order->updateStatus($data['status'], $data['notes'] ?? null);
        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function cancel(Request $request, Order $order)
    {
        $data = $request->validate(['reason' => 'nullable|string']);
        $order->cancel($data['reason'] ?? null);
        return back()->with('success', 'تم إلغاء الطلب');
    }

    public function exportPdf(Order $order)
    {
        $order->load(['user', 'vendor', 'items.product']);
        
        $pdf = Pdf::loadView('admin.orders.pdf', compact('order'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);

        return $pdf->download("order-{$order->order_number}.pdf");
    }
}
