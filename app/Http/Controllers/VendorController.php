<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function login()
    {
        return view('vendor.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('vendor')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('vendor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'البيانات المدخلة غير صحيحة.',
        ]);
    }

    public function signup()
    {
        return view('vendor.auth.signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'commercial_record' => 'required|string|unique:vendors',
            'address' => 'required|string',
            'city' => 'required|string',
            'governorate' => 'required|string',
            'business_categories' => 'required|array',
            'business_description' => 'nullable|string'
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'commercial_record' => $request->commercial_record,
            'address' => $request->address,
            'city' => $request->city,
            'governorate' => $request->governorate,
            'business_categories' => $request->business_categories,
            'business_description' => $request->business_description,
            'status' => 'pending'
        ]);

        return redirect()->route('vendor.login')
            ->with('success', 'تم إنشاء حسابك بنجاح. سيتم مراجعة طلبك والتواصل معك قريباً.');
    }

    public function dashboard()
    {
        $vendor = Auth::guard('vendor')->user();
        
        $stats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->active()->count(),
            'out_of_stock' => $vendor->products()->where('stock', 0)->count(),
            'total_orders' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()->pending()->count(),
            'total_sales' => $vendor->total_sales,
            'this_month_sales' => $vendor->orders()
                ->where('status', 'delivered')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount')
        ];

        $recentOrders = $vendor->orders()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $topProducts = $vendor->products()
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentOrders', 'topProducts'));
    }

    public function products()
    {
        $vendor = Auth::guard('vendor')->user();
        $products = $vendor->products()->latest()->paginate(10);

        return view('vendor.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('vendor.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'brand' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'unit' => 'required|string',
            'is_fresh' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $vendor = Auth::guard('vendor')->user();

        $productData = $request->all();
        $productData['vendor_id'] = $vendor->id;

        if ($request->hasFile('image')) {
            $productData['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($productData);

        return redirect()->route('vendor.products')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function orders()
    {
        $vendor = Auth::guard('vendor')->user();
        $orders = $vendor->orders()
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('vendor.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,shipped,delivered,cancelled'
        ]);

        // التحقق من أن الطلب ��خص المورد الحالي
        if ($order->vendor_id !== Auth::guard('vendor')->id()) {
            abort(403);
        }

        $order->updateStatus($request->status, $request->notes);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب بنجاح'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
