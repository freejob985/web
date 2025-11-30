<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function recent()
    {
        $numbers = session()->get('recent_orders', []);
        if (empty($numbers)) {
            return response()->json(['success' => true, 'orders' => []]);
        }
        $orders = Order::whereIn('order_number', $numbers)->with(['vendor', 'items.product'])->latest()->get();
        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($o) {
                return [
                    'id' => $o->id,
                    'order_number' => $o->order_number,
                    'status' => $o->status,
                    'status_label' => $o->status_label,
                    'total' => (float) $o->total_amount,
                    'created_at' => $o->created_at?->toDateTimeString(),
                    'items_count' => $o->items->sum('quantity'),
                ];
            })->values(),
        ]);
    }

    public function track(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['vendor', 'items.product', 'items.offer', 'user'])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'subtotal' => (float) $order->subtotal,
                'delivery_fee' => (float) $order->delivery_fee,
                'tax_amount' => (float) $order->tax_amount,
                'discount_amount' => (float) $order->discount_amount,
                'total' => (float) $order->total_amount,
                'currency' => $order->currency,
                'delivery_type' => $order->delivery_type,
                'delivery_type_label' => $order->delivery_type_label,
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'delivery_governorate' => $order->delivery_governorate,
                'delivery_phone' => $order->delivery_phone,
                'delivery_notes' => $order->delivery_notes,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'payment_reference' => $order->payment_reference,
                'requested_delivery_at' => $order->requested_delivery_at?->toDateTimeString(),
                'estimated_delivery_at' => $order->estimated_delivery_at?->toDateTimeString(),
                'delivered_at' => $order->delivered_at?->toDateTimeString(),
                'created_at' => $order->created_at?->toDateTimeString(),
                'notes' => $order->notes,
                'cancellation_reason' => $order->cancellation_reason,
                'cancelled_at' => $order->cancelled_at?->toDateTimeString(),
                'rating' => $order->rating,
                'review' => $order->review,
                'reviewed_at' => $order->reviewed_at?->toDateTimeString(),
                'vendor' => $order->vendor ? [
                    'id' => $order->vendor->id, 
                    'name' => $order->vendor->name,
                    'phone' => $order->vendor->phone,
                    'address' => $order->vendor->address,
                    'city' => $order->vendor->city,
                    'governorate' => $order->vendor->governorate
                ] : null,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone
                ] : null,
                'items' => $order->items->map(function ($item) {
                    // تحديد نوع العنصر (منتج أو عرض)
                    $isOffer = $item->type === 'offer' || $item->offer_id;
                    
                    return [
                        'id' => $item->id,
                        'type' => $item->type ?? ($isOffer ? 'offer' : 'product'),
                        'product' => $isOffer ? [
                            'id' => $item->offer_id,
                            'name' => $item->name ?? $item->product_name ?? 'عرض غير محدد',
                            'image' => $item->product_image,
                            'description' => null,
                            'category' => null,
                            'brand' => null,
                        ] : [
                            'id' => $item->product_id,
                            'name' => $item->product->name ?? $item->product_name ?? 'منتج غير محدد',
                            'image' => $item->product?->getMainImage() ?? $item->product_image,
                            'description' => $item->product?->description,
                            'category' => $item->product?->category,
                            'brand' => $item->product?->brand,
                        ],
                        'price' => (float) ($item->price ?? $item->unit_price),
                        'quantity' => (int) $item->quantity,
                        'total' => (float) ($item->total ?? ($item->price ?? $item->unit_price) * $item->quantity),
                        'notes' => $item->notes,
                    ];
                })->values(),
            ],
        ]);
    }

    public function details($id)
    {
        $order = Order::with(['vendor', 'items.product', 'items.offer', 'user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'subtotal' => (float) $order->subtotal,
                'delivery_fee' => (float) $order->delivery_fee,
                'tax_amount' => (float) $order->tax_amount,
                'discount_amount' => (float) $order->discount_amount,
                'total' => (float) $order->total_amount,
                'currency' => $order->currency,
                'delivery_type' => $order->delivery_type,
                'delivery_type_label' => $order->delivery_type_label,
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'delivery_governorate' => $order->delivery_governorate,
                'delivery_phone' => $order->delivery_phone,
                'delivery_notes' => $order->delivery_notes,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'payment_reference' => $order->payment_reference,
                'requested_delivery_at' => $order->requested_delivery_at?->toDateTimeString(),
                'estimated_delivery_at' => $order->estimated_delivery_at?->toDateTimeString(),
                'delivered_at' => $order->delivered_at?->toDateTimeString(),
                'created_at' => $order->created_at?->toDateTimeString(),
                'notes' => $order->notes,
                'cancellation_reason' => $order->cancellation_reason,
                'cancelled_at' => $order->cancelled_at?->toDateTimeString(),
                'rating' => $order->rating,
                'review' => $order->review,
                'reviewed_at' => $order->reviewed_at?->toDateTimeString(),
                'vendor' => $order->vendor ? [
                    'id' => $order->vendor->id, 
                    'name' => $order->vendor->name,
                    'phone' => $order->vendor->phone,
                    'address' => $order->vendor->address,
                    'city' => $order->vendor->city,
                    'governorate' => $order->vendor->governorate
                ] : null,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone
                ] : null,
                'items' => $order->items->map(function ($item) {
                    // تحديد نوع العنصر (منتج أو عرض)
                    $isOffer = $item->type === 'offer' || $item->offer_id;
                    
                    return [
                        'id' => $item->id,
                        'type' => $item->type ?? ($isOffer ? 'offer' : 'product'),
                        'product' => $isOffer ? [
                            'id' => $item->offer_id,
                            'name' => $item->name ?? $item->product_name ?? 'عرض غير محدد',
                            'image' => $item->product_image,
                            'description' => null,
                            'category' => null,
                            'brand' => null,
                        ] : [
                            'id' => $item->product_id,
                            'name' => $item->product->name ?? $item->product_name ?? 'منتج غير محدد',
                            'image' => $item->product?->getMainImage() ?? $item->product_image,
                            'description' => $item->product?->description,
                            'category' => $item->product?->category,
                            'brand' => $item->product?->brand,
                        ],
                        'price' => (float) ($item->price ?? $item->unit_price),
                        'quantity' => (int) $item->quantity,
                        'total' => (float) ($item->total ?? ($item->price ?? $item->unit_price) * $item->quantity),
                        'notes' => $item->notes,
                    ];
                })->values(),
            ],
        ]);
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['vendor', 'items.product', 'items.offer', 'user'])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'subtotal' => (float) $order->subtotal,
                'delivery_fee' => (float) $order->delivery_fee,
                'tax_amount' => (float) $order->tax_amount,
                'discount_amount' => (float) $order->discount_amount,
                'total' => (float) $order->total_amount,
                'currency' => $order->currency,
                'delivery_type' => $order->delivery_type,
                'delivery_type_label' => $order->delivery_type_label,
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'delivery_governorate' => $order->delivery_governorate,
                'delivery_phone' => $order->delivery_phone,
                'delivery_notes' => $order->delivery_notes,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'payment_reference' => $order->payment_reference,
                'requested_delivery_at' => $order->requested_delivery_at?->toDateTimeString(),
                'estimated_delivery_at' => $order->estimated_delivery_at?->toDateTimeString(),
                'delivered_at' => $order->delivered_at?->toDateTimeString(),
                'created_at' => $order->created_at?->toDateTimeString(),
                'notes' => $order->notes,
                'cancellation_reason' => $order->cancellation_reason,
                'cancelled_at' => $order->cancelled_at?->toDateTimeString(),
                'rating' => $order->rating,
                'review' => $order->review,
                'reviewed_at' => $order->reviewed_at?->toDateTimeString(),
                'vendor' => $order->vendor ? [
                    'id' => $order->vendor->id, 
                    'name' => $order->vendor->name,
                    'phone' => $order->vendor->phone,
                    'address' => $order->vendor->address,
                    'city' => $order->vendor->city,
                    'governorate' => $order->vendor->governorate
                ] : null,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone
                ] : null,
                'items' => $order->items->map(function ($item) {
                    // تحديد نوع العنصر (منتج أو عرض)
                    $isOffer = $item->type === 'offer' || $item->offer_id;
                    
                    return [
                        'id' => $item->id,
                        'type' => $item->type ?? ($isOffer ? 'offer' : 'product'),
                        'product' => $isOffer ? [
                            'id' => $item->offer_id,
                            'name' => $item->name ?? $item->product_name ?? 'عرض غير محدد',
                            'image' => $item->product_image,
                            'description' => null,
                            'category' => null,
                            'brand' => null,
                        ] : [
                            'id' => $item->product_id,
                            'name' => $item->product->name ?? $item->product_name ?? 'منتج غير محدد',
                            'image' => $item->product?->getMainImage() ?? $item->product_image,
                            'description' => $item->product?->description,
                            'category' => $item->product?->category,
                            'brand' => $item->product?->brand,
                        ],
                        'price' => (float) ($item->price ?? $item->unit_price),
                        'quantity' => (int) $item->quantity,
                        'total' => (float) ($item->total ?? ($item->price ?? $item->unit_price) * $item->quantity),
                        'notes' => $item->notes,
                    ];
                })->values(),
            ],
        ]);
    }

    public function downloadInvoice(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['vendor', 'items.product', 'items.offer', 'user'])
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        }

        try {
            // Generate HTML invoice with proper Arabic support
            $html = view('invoices.order-html', compact('order'))->render();
            
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'inline; filename="invoice-' . $orderNumber . '.html"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0')
                ->header('X-Content-Type-Options', 'nosniff');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'خطأ في إنشاء الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }
}
