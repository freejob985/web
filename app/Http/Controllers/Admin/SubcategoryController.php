<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::with(['category'])
            ->withCount('products')
            ->ordered()
            ->paginate(20);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name_ar);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subcategories', 'public');
        }

        Subcategory::create($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'تم إنشاء القسم الفرعي بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        $subcategory->load(['category', 'products']);
        return view('admin.subcategories.show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name_ar);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subcategories', 'public');
        }

        $subcategory->update($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'تم تحديث القسم الفرعي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        if ($subcategory->products()->count() > 0) {
            return redirect()->route('admin.subcategories.index')
                ->with('error', 'لا يمكن حذف القسم الفرعي لوجود منتجات مرتبطة به');
        }

        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'تم حذف القسم الفرعي بنجاح');
    }
}
