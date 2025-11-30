<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Notification;
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
    /**
     * Get vendor dashboard statistics
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            // Get vendor ID from session or request
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $vendor = Vendor::find($vendorId);
            if (!$vendor) {
                return response()->json(['success' => false, 'message' => 'Vendor not found'], 404);
            }

            // Calculate current month stats
            $currentMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Total sales - ALL TIME
            $totalSalesAllTime = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            // Total sales current month
            $totalSales = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $currentMonth)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            // Total sales last month
            $totalSalesLastMonth = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            // Calculate percentage change
            $salesChange = $totalSalesLastMonth > 0 
                ? (($totalSales - $totalSalesLastMonth) / $totalSalesLastMonth) * 100 
                : 0;

            // Total orders - ALL TIME
            $totalOrders = Order::where('vendor_id', $vendorId)
                ->count();

            // Pending orders
            $pendingOrders = Order::where('vendor_id', $vendorId)
                ->where('status', 'pending')
                ->count();

            // Processing orders
            $processingOrders = Order::where('vendor_id', $vendorId)
                ->where('status', 'processing')
                ->count();

            // Completed orders
            $completedOrders = Order::where('vendor_id', $vendorId)
                ->where('status', 'delivered')
                ->count();

            // New orders current month
            $newOrders = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $currentMonth)
                ->count();

            // New orders last month
            $newOrdersLastMonth = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->count();

            $ordersChange = $newOrdersLastMonth > 0 
                ? $newOrders - $newOrdersLastMonth 
                : $newOrders;

            // Total products (all)
            $totalProducts = Product::where('vendor_id', $vendorId)->count();

            // Active approved products
            $activeProducts = Product::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->where('status', 'approved')
                ->count();

            // Pending approval products
            $pendingProducts = Product::where('vendor_id', $vendorId)
                ->where('status', 'pending')
                ->count();

            // Out of stock products
            $outOfStockProducts = Product::where('vendor_id', $vendorId)
                ->where('stock', '<=', 0)
                ->count();

            // Active approved products last month
            $activeProductsLastMonth = Product::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->where('status', 'approved')
                ->where('created_at', '<=', $lastMonthEnd)
                ->count();

            $productsChange = $activeProductsLastMonth > 0 
                ? $activeProducts - $activeProductsLastMonth 
                : $activeProducts;

            // New customers (unique customers who made orders)
            $newCustomers = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $currentMonth)
                ->distinct('user_id')
                ->count('user_id');

            // Total unique customers (all time)
            $totalCustomers = Order::where('vendor_id', $vendorId)
                ->distinct('user_id')
                ->count('user_id');

            // New customers last month
            $newCustomersLastMonth = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->distinct('user_id')
                ->count('user_id');

            $customersChange = $newCustomersLastMonth > 0 
                ? $newCustomers - $newCustomersLastMonth 
                : $newCustomers;

            // Average order value
            $avgOrderValue = $totalOrders > 0 ? $totalSalesAllTime / $totalOrders : 0;

            // Today's stats
            $todayStart = Carbon::now()->startOfDay();
            $todayOrders = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $todayStart)
                ->count();
            $todaySales = Order::where('vendor_id', $vendorId)
                ->where('created_at', '>=', $todayStart)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

            return response()->json([
                'success' => true,
                'data' => [
                    // Main stats (for dashboard cards)
                    'total_orders' => $totalOrders,
                    'pending_orders' => $pendingOrders,
                    'total_products' => $totalProducts,
                    'total_sales' => round($totalSalesAllTime, 2),
                    
                    // Detailed stats
                    'processing_orders' => $processingOrders,
                    'completed_orders' => $completedOrders,
                    'active_products' => $activeProducts,
                    'pending_products' => $pendingProducts,
                    'out_of_stock_products' => $outOfStockProducts,
                    'total_customers' => $totalCustomers,
                    'avg_order_value' => round($avgOrderValue, 2),
                    
                    // Monthly stats (with changes)
                    'monthly_sales' => round($totalSales, 2),
                    'monthly_sales_change' => round($salesChange, 1),
                    'monthly_orders' => $newOrders,
                    'monthly_orders_change' => $ordersChange,
                    'monthly_customers' => $newCustomers,
                    'monthly_customers_change' => $customersChange,
                    'monthly_products_change' => $productsChange,
                    
                    // Today's stats
                    'today_orders' => $todayOrders,
                    'today_sales' => round($todaySales, 2),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent orders for vendor dashboard
     */
    public function recentOrders(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $orders = Order::where('vendor_id', $vendorId)
                ->with(['items' => function($query) {
                    $query->select('order_id', 'product_name', 'quantity', 'price', 'product_image');
                }, 'user' => function($query) {
                    $query->select('id', 'name', 'phone');
                }])
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'customer_name' => $order->user->name ?? 'عميل',
                        'customer_phone' => $order->user->phone ?? $order->delivery_phone,
                        'customer_address' => $order->delivery_address,
                        'total' => (float) $order->total_amount,
                        'status' => $order->status,
                        'status_label' => $this->getStatusLabel($order->status),
                        'items_count' => $order->items->count(),
                        'created_at' => $order->created_at->toISOString(),
                        'items' => $order->items->map(function($item) {
                            return [
                                'name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'price' => (float) $item->price,
                                'image' => $item->product_image
                            ];
                        })
                    ];
                });

            return response()->json([
                'success' => true,
                'orders' => $orders // Changed from 'data' to 'orders' to match API service
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recent orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top products for vendor dashboard
     */
    public function topProducts(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $topProducts = Product::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->withCount(['orderItems as sales_count' => function($query) {
                    $query->whereHas('order', function($q) {
                        $q->where('status', '!=', 'cancelled');
                    });
                }])
                ->withSum(['orderItems as revenue' => function($query) {
                    $query->whereHas('order', function($q) {
                        $q->where('status', '!=', 'cancelled');
                    });
                }], 'total_price')
                ->orderBy('sales_count', 'desc')
                ->limit(3)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sales_count' => $product->sales_count ?? 0,
                        'revenue' => $product->revenue ?? 0,
                        'rating' => $product->rating ?? 0,
                        'image' => $product->image
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $topProducts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching top products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**     * Get vendor notifications (last 3)
     */
    public function notifications(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Get last 3 notifications for the vendor
            $notifications = Notification::forVendor($vendorId)
                ->recent(3)
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'unread' => !$notification->is_read,
                        'icon' => $notification->icon,
                        'color' => $notification->color,
                        'created_at' => $notification->created_at->toISOString(),
                        'time_ago' => $notification->time_ago,
                        'data' => $notification->data
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read for vendor
     */
    public function markNotificationsRead(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Mark all unread notifications for this vendor as read
            $updatedCount = Notification::forVendor($vendorId)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديد جميع الإشعارات كمقروءة',
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count for vendor
     */
    public function getUnreadCount(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $count = Notification::forVendor($vendorId)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching unread count: ' . $e->getMessage()
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
            'processing' => 'قيد التحضير',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي'
        ];

        return $labels[$status] ?? $status;
    }
}
