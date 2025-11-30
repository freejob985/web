<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by discount type
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }

        // Search by code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $coupons = $query->latest()->paginate(20)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $products = Product::select('id', 'name')->get();
        $categories = Category::select('id', 'name_ar as name')->get();
        
        return view('admin.coupons.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit_type' => 'required|in:unlimited,limited',
            'usage_limit' => 'nullable|required_if:usage_limit_type,limited|integer|min:1',
            'applicable_type' => 'required|in:all_products,specific_products,specific_categories',
            'applicable_products' => 'nullable|required_if:applicable_type,specific_products|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|required_if:applicable_type,specific_categories|array',
            'applicable_categories.*' => 'exists:categories,id',
            'status' => 'required|in:active,inactive,expired',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at'
        ]);

        // Validate maximum discount for percentage type
        if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'نسبة الخصم لا يمكن أن تكون أكثر من 100%']);
        }

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم إنشاء الكوبون بنجاح');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['applicable_products', 'applicable_categories']);
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        $products = Product::select('id', 'name')->get();
        $categories = Category::select('id', 'name_ar as name')->get();
        
        return view('admin.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit_type' => 'required|in:unlimited,limited',
            'usage_limit' => 'nullable|required_if:usage_limit_type,limited|integer|min:1',
            'applicable_type' => 'required|in:all_products,specific_products,specific_categories',
            'applicable_products' => 'nullable|required_if:applicable_type,specific_products|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|required_if:applicable_type,specific_categories|array',
            'applicable_categories.*' => 'exists:categories,id',
            'status' => 'required|in:active,inactive,expired',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at'
        ]);

        // Validate maximum discount for percentage type
        if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'نسبة الخصم لا يمكن أن تكون أكثر من 100%']);
        }

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم تحديث الكوبون بنجاح');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم حذف الكوبون بنجاح');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update([
            'status' => $coupon->status === 'active' ? 'inactive' : 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الكوبون',
            'status' => $coupon->status
        ]);
    }
}