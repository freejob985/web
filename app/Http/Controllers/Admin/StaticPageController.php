<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pages = StaticPage::ordered()->get();
        return view('admin.static-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.static-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'slug' => 'required|string|max:255|unique:static_pages,slug',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'is_fixed' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $page = StaticPage::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الصفحة بنجاح',
            'data' => $page
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(StaticPage $staticPage): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $staticPage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaticPage $staticPage): View
    {
        return view('admin.static-pages.edit', compact('staticPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaticPage $staticPage): JsonResponse
    {
        // منع تعديل الصفحات الثابتة
        if ($staticPage->is_fixed) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعديل الصفحات الثابتة'
            ], 403);
        }

        $request->validate([
            'slug' => 'required|string|max:255|unique:static_pages,slug,' . $staticPage->id,
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $staticPage->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصفحة بنجاح',
            'data' => $staticPage
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaticPage $staticPage): JsonResponse
    {
        // منع حذف الصفحات الثابتة
        if ($staticPage->is_fixed) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الصفحات الثابتة'
            ], 403);
        }

        $staticPage->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الصفحة بنجاح'
        ]);
    }

    /**
     * Toggle page status
     */
    public function toggleStatus(StaticPage $staticPage): JsonResponse
    {
        // منع تغيير حالة الصفحات الثابتة
        if ($staticPage->is_fixed) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تغيير حالة الصفحات الثابتة'
            ], 403);
        }

        $staticPage->update(['is_active' => !$staticPage->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير حالة الصفحة بنجاح',
            'data' => $staticPage
        ]);
    }
}
