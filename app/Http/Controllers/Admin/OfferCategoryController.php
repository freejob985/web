<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfferCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = OfferCategory::ordered()->paginate(15);
        return view('admin.offer-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.offer-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('offer-categories', 'public');
            $data['image'] = $imagePath;
        }

        OfferCategory::create($data);

        return redirect()->route('admin.offer-categories.index')
            ->with('success', 'تم إنشاء قسم العروض بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferCategory $offerCategory)
    {
        $offerCategory->load('offers');
        return view('admin.offer-categories.show', compact('offerCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfferCategory $offerCategory)
    {
        return view('admin.offer-categories.edit', compact('offerCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfferCategory $offerCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('offer-categories', 'public');
            $data['image'] = $imagePath;
        }

        $offerCategory->update($data);

        return redirect()->route('admin.offer-categories.index')
            ->with('success', 'تم تحديث قسم العروض بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferCategory $offerCategory)
    {
        $offerCategory->delete();

        return redirect()->route('admin.offer-categories.index')
            ->with('success', 'تم حذف قسم العروض بنجاح');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(OfferCategory $offerCategory)
    {
        $offerCategory->update(['is_active' => !$offerCategory->is_active]);
        
        $status = $offerCategory->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()->with('success', "تم {$status} قسم العروض بنجاح");
    }
}
