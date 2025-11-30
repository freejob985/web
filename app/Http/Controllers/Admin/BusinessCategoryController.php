<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::orderBy('sort_order')->paginate(10);
        return view('admin.business-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.business-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        BusinessCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.business-categories.index')
            ->with('success', 'تم إنشاء فئة الأعمال بنجاح');
    }

    public function edit(BusinessCategory $businessCategory)
    {
        return view('admin.business-categories.edit', compact('businessCategory'));
    }

    public function update(Request $request, BusinessCategory $businessCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $businessCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? $businessCategory->is_active,
            'sort_order' => $request->sort_order ?? $businessCategory->sort_order,
        ]);

        return redirect()->route('admin.business-categories.index')
            ->with('success', 'تم تحديث فئة الأعمال بنجاح');
    }

    public function destroy(BusinessCategory $businessCategory)
    {
        $businessCategory->delete();
        return redirect()->route('admin.business-categories.index')
            ->with('success', 'تم حذف فئة الأعمال بنجاح');
    }
}