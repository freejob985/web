<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VendorReportController extends Controller
{
    /**
     * Get general reports overview for vendor
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Get date range (default to last 30 days)
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();

            // Get basic statistics
            $totalSales = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');

            $totalOrders = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $activeProducts = Product::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->where('status', 'approved')
                ->count();

            $totalProducts = Product::where('vendor_id', $vendorId)
                ->where('status', 'approved')
                ->count();

            // Get recent orders
            $recentOrders = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'order_number', 'total_amount', 'status', 'created_at']);

            // Get top products
            $topProducts = OrderItem::whereHas('order', function($query) use ($vendorId, $startDate, $endDate) {
                $query->where('vendor_id', $vendorId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('product_name, SUM(quantity) as total_quantity, SUM(unit_price * quantity) as total_revenue')
            ->groupBy('product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => [
                        'total_sales' => $totalSales,
                        'total_orders' => $totalOrders,
                        'active_products' => $activeProducts,
                        'total_products' => $totalProducts,
                        'average_order_value' => $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0
                    ],
                    'recent_orders' => $recentOrders,
                    'top_products' => $topProducts,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reports: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales report for vendor
     */
    public function sales(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Get date range
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
            $period = $request->get('period', 'daily'); // daily, weekly, monthly

            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();

            // Base query for orders
            $query = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate]);

            // Group by period
            switch ($period) {
                case 'daily':
                    $salesData = $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as orders_count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
                    break;
                case 'weekly':
                    $salesData = $query->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(total_amount) as total_sales, COUNT(*) as orders_count')
                        ->groupBy('year', 'week')
                        ->orderBy('year', 'week')
                        ->get();
                    break;
                case 'monthly':
                    $salesData = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total_sales, COUNT(*) as orders_count')
                        ->groupBy('year', 'month')
                        ->orderBy('year', 'month')
                        ->get();
                    break;
                default:
                    $salesData = $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as orders_count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
            }

            // Calculate totals
            $totalSales = $salesData->sum('total_sales');
            $totalOrders = $salesData->sum('orders_count');
            $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

            // Get sales by status
            $salesByStatus = Order::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, SUM(total_amount) as total_sales, COUNT(*) as orders_count')
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->status => [
                        'total_sales' => $item->total_sales,
                        'orders_count' => $item->orders_count
                    ]];
                });

            // Get top selling products
            $topProducts = OrderItem::whereHas('order', function($query) use ($vendorId, $startDate, $endDate) {
                $query->where('vendor_id', $vendorId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('product_name, SUM(quantity) as total_quantity, SUM(unit_price * quantity) as total_revenue')
            ->groupBy('product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'period' => $period,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'total_sales' => $totalSales,
                    'total_orders' => $totalOrders,
                    'average_order_value' => round($averageOrderValue, 2),
                    'sales_data' => $salesData,
                    'sales_by_status' => $salesByStatus,
                    'top_products' => $topProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products report for vendor
     */
    public function products(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Get date range
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
            $category = $request->get('category');

            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();

            // Base query for approved products
            $query = Product::where('vendor_id', $vendorId)
                ->where('status', 'approved');

            if ($category) {
                $query->where('category_id', $category);
            }

            // Get product statistics
            $productStats = $query->clone()->selectRaw('
                COUNT(*) as total_products,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_products,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_products,
                SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_products,
                SUM(CASE WHEN is_fresh = 1 THEN 1 ELSE 0 END) as fresh_products
            ')->first();

            // Get products with sales data
            $productsWithSales = $query->clone()->withCount(['orderItems as sales_count' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }])
            ->withSum(['orderItems as revenue' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }], 'total_price')
            ->orderBy('sales_count', 'desc')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'is_active' => $product->is_active,
                    'is_featured' => $product->is_featured,
                    'is_fresh' => $product->is_fresh,
                    'sales_count' => $product->sales_count ?? 0,
                    'revenue' => $product->revenue ?? 0,
                    'rating' => $product->rating ?? 0,
                    'image' => $product->image
                ];
            });

            // Get products by category
            $productsByCategory = Product::where('vendor_id', $vendorId)
                ->where('status', 'approved')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('categories.name_ar as category_name, COUNT(*) as products_count')
                ->groupBy('categories.id', 'categories.name_ar')
                ->orderBy('products_count', 'desc')
                ->get();

            // Get products by type
            $productsByType = Product::where('vendor_id', $vendorId)
                ->where('status', 'approved')
                ->selectRaw('
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured,
                    SUM(CASE WHEN is_fresh = 1 THEN 1 ELSE 0 END) as fresh
                ')
                ->first();

            // Get low stock products
            $lowStockProducts = Product::where('vendor_id', $vendorId)
                ->where('status', 'approved')
                ->where('stock', '<=', 10)
                ->where('is_active', true)
                ->select('id', 'name', 'stock', 'price')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'product_statistics' => $productStats,
                    'products_with_sales' => $productsWithSales,
                    'products_by_category' => $productsByCategory,
                    'products_by_type' => $productsByType,
                    'low_stock_products' => $lowStockProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get revenue report for vendor
     */
    public function revenue(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Get date range
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();

            // Get revenue data
            $revenueData = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('
                    DATE(created_at) as date,
                    SUM(total_amount) as daily_revenue,
                    COUNT(*) as daily_orders,
                    AVG(total_amount) as average_order_value
                ')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Calculate totals
            $totalRevenue = $revenueData->sum('daily_revenue');
            $totalOrders = $revenueData->sum('daily_orders');
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Get revenue by payment method
            $revenueByPaymentMethod = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('payment_method, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                ->groupBy('payment_method')
                ->get();

            // Get revenue by delivery type
            $revenueByDeliveryType = Order::where('vendor_id', $vendorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('delivery_type, SUM(total_amount) as revenue, COUNT(*) as orders_count')
                ->groupBy('delivery_type')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $totalOrders,
                    'average_order_value' => round($averageOrderValue, 2),
                    'revenue_data' => $revenueData,
                    'revenue_by_payment_method' => $revenueByPaymentMethod,
                    'revenue_by_delivery_type' => $revenueByDeliveryType
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching revenue report: ' . $e->getMessage()
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
}
