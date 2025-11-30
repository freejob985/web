<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::with('category')->ordered()->paginate(15);
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = OfferCategory::active()->ordered()->get();
        return view('admin.offers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'offer_category_id' => 'required|exists:offer_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'original_price' => 'required|numeric|min:0',
            'new_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'discount_type' => 'required|in:percentage,fixed,buy_x_get_y',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'is_flash_sale' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('offers', 'public');
            $data['image'] = $imagePath;
        }

        // Calculate discount percentage if not provided
        if (!$data['discount_percentage'] && $data['original_price'] > 0) {
            $data['discount_percentage'] = round((($data['original_price'] - $data['new_price']) / $data['original_price']) * 100);
        }

        Offer::create($data);

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم إنشاء العرض بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        $offer->load('category');
        return view('admin.offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        $categories = OfferCategory::active()->ordered()->get();
        return view('admin.offers.edit', compact('offer', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'offer_category_id' => 'required|exists:offer_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'original_price' => 'required|numeric|min:0',
            'new_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'discount_type' => 'required|in:percentage,fixed,buy_x_get_y',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'is_flash_sale' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('offers', 'public');
            $data['image'] = $imagePath;
        }

        // Calculate discount percentage if not provided
        if (!$data['discount_percentage'] && $data['original_price'] > 0) {
            $data['discount_percentage'] = round((($data['original_price'] - $data['new_price']) / $data['original_price']) * 100);
        }

        $offer->update($data);

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);
        
        $status = $offer->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()->with('success', "تم {$status} العرض بنجاح");
    }

    /**
     * Toggle popular status
     */
    public function togglePopular(Offer $offer)
    {
        $offer->update(['is_featured' => !$offer->is_featured]);
        
        $status = $offer->is_featured ? 'مميز' : 'عادي';
        return redirect()->back()->with('success', "تم تعيين العرض كـ {$status} بنجاح");
    }

    /**
     * Toggle flash sale status
     */
    public function toggleFlashSale(Offer $offer)
    {
        $offer->update(['is_limited_time' => !$offer->is_limited_time]);
        
        $status = $offer->is_limited_time ? 'عرض برق' : 'عرض عادي';
        return redirect()->back()->with('success', "تم تعيين العرض كـ {$status} بنجاح");
    }
}
