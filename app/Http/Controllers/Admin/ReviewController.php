<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\VendorReview;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * عرض صفحة إدارة تقييمات المنتجات
     */
    public function productReviews(Request $request): View
    {
        $query = ProductReview::with(['product', 'user', 'vendor'])
            ->orderBy('created_at', 'desc');

        // تصفية حسب الحالة
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // تصفية حسب التقييم
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20);

        return view('admin.reviews.products', compact('reviews'));
    }

    /**
     * عرض صفحة إدارة تقييمات الموردين
     */
    public function vendorReviews(Request $request): View
    {
        $query = VendorReview::with(['vendor', 'user'])
            ->orderBy('created_at', 'desc');

        // تصفية حسب الحالة
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // تصفية حسب التقييم
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20);

        return view('admin.reviews.vendors', compact('reviews'));
    }

    /**
     * الموافقة على تقييم منتج
     */
    public function approveProductReview($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'تم الموافقة على التقييم بنجاح');
    }

    /**
     * رفض تقييم منتج
     */
    public function rejectProductReview($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'تم رفض التقييم بنجاح');
    }

    /**
     * الموافقة على تقييم مورد
     */
    public function approveVendorReview($id)
    {
        $review = VendorReview::findOrFail($id);
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'تم الموافقة على التقييم بنجاح');
    }

    /**
     * رفض تقييم مورد
     */
    public function rejectVendorReview($id)
    {
        $review = VendorReview::findOrFail($id);
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'تم رفض التقييم بنجاح');
    }

    /**
     * حذف تقييم منتج
     */
    public function deleteProductReview($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * حذف تقييم مورد
     */
    public function deleteVendorReview($id)
    {
        $review = VendorReview::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * إحصائيات التقييمات
     */
    public function stats(): View
    {
        $stats = [
            'total_product_reviews' => ProductReview::count(),
            'approved_product_reviews' => ProductReview::where('is_approved', true)->count(),
            'pending_product_reviews' => ProductReview::where('is_approved', false)->count(),
            'total_vendor_reviews' => VendorReview::count(),
            'approved_vendor_reviews' => VendorReview::where('is_approved', true)->count(),
            'pending_vendor_reviews' => VendorReview::where('is_approved', false)->count(),
            'average_product_rating' => ProductReview::where('is_approved', true)->avg('rating'),
            'average_vendor_rating' => VendorReview::where('is_approved', true)->avg('rating'),
        ];

        return view('admin.reviews.stats', compact('stats'));
    }
}
