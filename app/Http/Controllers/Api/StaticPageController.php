<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StaticPageController extends Controller
{
    /**
     * عرض جميع الصفحات الثابتة
     */
    public function index(): JsonResponse
    {
        $pages = StaticPage::active()
            ->ordered()
            ->select('id', 'slug', 'title', 'meta_description', 'is_fixed', 'sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * عرض صفحة محددة بالـ slug
     */
    public function show(string $slug): JsonResponse
    {
        $page = StaticPage::findBySlug($slug);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'الصفحة غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * الحصول على صفحة شروط الخدمة
     */
    public function terms(): JsonResponse
    {
        $page = StaticPage::findBySlug('terms');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'صفحة شروط الخدمة غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * الحصول على صفحة سياسة الخصوصية
     */
    public function privacy(): JsonResponse
    {
        $page = StaticPage::findBySlug('privacy');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'صفحة سياسة الخصوصية غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * الحصول على صفحة سياسة الاسترداد
     */
    public function refund(): JsonResponse
    {
        $page = StaticPage::findBySlug('refund');

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'صفحة سياسة الاسترداد غير موجودة'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * الحصول على الصفحات الثابتة فقط
     */
    public function fixed(): JsonResponse
    {
        $pages = StaticPage::fixed()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * الحصول على الصفحات القابلة للتعديل
     */
    public function editable(): JsonResponse
    {
        $pages = StaticPage::editable()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }
}
