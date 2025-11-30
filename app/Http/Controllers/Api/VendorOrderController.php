<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class VendorOrderController extends Controller
{
    /**
     * Get vendor orders with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $query = Order::where('vendor_id', $vendorId)
                ->with([
                    'items' => function($query) {
                        $query->select('order_id', 'product_name', 'quantity', 'unit_price', 'total_price', 'product_image');
                    },
                    'user' => function($query) {
                        $query->select('id', 'name', 'phone', 'email');
                    }
                ]);

            // Apply filters
            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('order_number', 'like', '%' . $request->search . '%')
                      ->orWhereHas('user', function($userQuery) use ($request) {
                          $userQuery->where('name', 'like', '%' . $request->search . '%')
                                   ->orWhere('phone', 'like', '%' . $request->search . '%');
                      });
                });
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->where('created_at', '>=', Carbon::parse($request->date_from));
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $orders = $query->paginate($perPage);

            // Transform orders
            $transformedOrders = $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? 'غير محدد',
                    'customer_phone' => $order->user->phone ?? $order->delivery_phone ?? 'غير محدد',
                    'customer_email' => $order->user->email ?? null,
                    'customer_address' => $order->delivery_address,
                    'delivery_city' => $order->delivery_city,
                    'delivery_governorate' => $order->delivery_governorate,
                    'delivery_notes' => $order->delivery_notes,
                    'delivery_type' => $order->delivery_type,
                    'delivery_type_label' => $this->getDeliveryTypeLabel($order->delivery_type),
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'payment_status_label' => $this->getPaymentStatusLabel($order->payment_status),
                    'subtotal' => $order->subtotal,
                    'delivery_fee' => $order->delivery_fee,
                    'tax_amount' => $order->tax_amount,
                    'discount_amount' => $order->discount_amount,
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'status_label' => $this->getStatusLabel($order->status),
                    'items_count' => $order->items->count(),
                    'created_at' => $order->created_at->toISOString(),
                    'requested_delivery_at' => $order->requested_delivery_at?->toISOString(),
                    'estimated_delivery_at' => $order->estimated_delivery_at?->toISOString(),
                    'delivered_at' => $order->delivered_at?->toISOString(),
                    'notes' => $order->notes,
                    'items' => $order->items->map(function($item) {
                        return [
                            'name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                            'image' => $item->product_image
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'orders' => $transformedOrders,
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific order details
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $order = Order::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->with([
                    'items' => function($query) {
                        $query->select('order_id', 'product_name', 'quantity', 'unit_price', 'total_price', 'product_image');
                    },
                    'user' => function($query) {
                        $query->select('id', 'name', 'phone', 'email');
                    }
                ])
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $transformedOrder = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->name ?? 'غير محدد',
                'customer_phone' => $order->user->phone ?? $order->delivery_phone ?? 'غير محدد',
                'customer_email' => $order->user->email ?? null,
                'customer_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'delivery_governorate' => $order->delivery_governorate,
                'delivery_notes' => $order->delivery_notes,
                'delivery_type' => $order->delivery_type,
                'delivery_type_label' => $this->getDeliveryTypeLabel($order->delivery_type),
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'payment_status_label' => $this->getPaymentStatusLabel($order->payment_status),
                'subtotal' => $order->subtotal,
                'delivery_fee' => $order->delivery_fee,
                'tax_amount' => $order->tax_amount,
                'discount_amount' => $order->discount_amount,
                'total' => $order->total_amount,
                'status' => $order->status,
                'status_label' => $this->getStatusLabel($order->status),
                'items_count' => $order->items->count(),
                'created_at' => $order->created_at->toISOString(),
                'requested_delivery_at' => $order->requested_delivery_at?->toISOString(),
                'estimated_delivery_at' => $order->estimated_delivery_at?->toISOString(),
                'delivered_at' => $order->delivered_at?->toISOString(),
                'notes' => $order->notes,
                'items' => $order->items->map(function($item) {
                    return [
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                        'image' => $item->product_image
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'order' => $transformedOrder
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $order = Order::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,preparing,ready,shipped,delivered,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $oldStatus = $order->status;
            $order->status = $request->status;
            $order->save();

            // Log status change
            Log::info("Order {$order->order_number} status changed from {$oldStatus} to {$order->status} by vendor {$vendorId}");

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'status_label' => $this->getStatusLabel($order->status)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics for vendor
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $currentMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Total orders
            $totalOrders = Order::where('vendor_id', $vendorId)->count();

            // Orders this month
            $ordersThisMonth = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $currentMonth)
                ->count();

            // Orders last month
            $ordersLastMonth = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->count();

            // Total revenue
            $totalRevenue = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            // Revenue this month
            $revenueThisMonth = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $currentMonth)
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            // Revenue last month
            $revenueLastMonth = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            // Orders by status
            $ordersByStatus = Order::where('vendor_id', $vendorId)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_orders' => $totalOrders,
                    'orders_this_month' => $ordersThisMonth,
                    'orders_last_month' => $ordersLastMonth,
                    'orders_change' => $ordersLastMonth > 0 ? $ordersThisMonth - $ordersLastMonth : $ordersThisMonth,
                    'total_revenue' => $totalRevenue,
                    'revenue_this_month' => $revenueThisMonth,
                    'revenue_last_month' => $revenueLastMonth,
                    'revenue_change' => $revenueLastMonth > 0 ? $revenueThisMonth - $revenueLastMonth : $revenueThisMonth,
                    'orders_by_status' => $ordersByStatus
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching order statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor ID from authenticated user
     */
    private function getVendorId(Request $request): ?int
    {
        // Get from authenticated vendor (middleware ensures this exists)
        $vendor = $request->user('vendor');
        if ($vendor) {
            return $vendor->id;
        }

        return null;
    }

    /**
     * Get status label in Arabic
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'pending' => 'في الانتظار',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التحضير',
            'ready' => 'جاهز',
            'shipped' => 'قيد التوصيل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get delivery type label in Arabic
     */
    private function getDeliveryTypeLabel(string $deliveryType): string
    {
        $labels = [
            'immediate' => 'فوري (30-60 دقيقة)',
            'fast' => 'سريع (1-2 ساعة)',
            'scheduled' => 'مجدول',
            'free' => 'مجاني (2-4 ساعات)'
        ];

        return $labels[$deliveryType] ?? $deliveryType;
    }

    /**
     * Get payment status label in Arabic
     */
    private function getPaymentStatusLabel(string $paymentStatus): string
    {
        $labels = [
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل',
            'refunded' => 'مسترد'
        ];

        return $labels[$paymentStatus] ?? $paymentStatus;
    }
}
