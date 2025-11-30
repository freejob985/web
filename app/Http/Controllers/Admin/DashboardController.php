<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'vendors' => Vendor::count(),
            'users' => User::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'sales_total' => Order::where('status', 'delivered')->sum('total_amount'),
        ];

        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->limit(10)
            ->get();

        $topProducts = Product::orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();

        $orderStatusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التحضير',
            'ready' => 'جاهز',
            'shipped' => 'قيد التوصيل',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
        ];

        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
        $rawSales = Order::where('status', 'delivered')
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        $salesLast7Days = $days->mapWithKeys(function ($d) use ($rawSales) {
            return [$d => (float) ($rawSales[$d] ?? 0)];
        })->toArray();
        $salesMax = max($salesLast7Days) ?: 0;

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'orderStatusCounts',
            'statusLabels',
            'salesLast7Days',
            'salesMax'
        ));
    }
}
