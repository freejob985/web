<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Vendor;
use App\Models\VendorReview;
use App\Models\ReviewHelpfulness;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'storeProductReview', 
            'storeVendorReview', 
            'updateProductReview', 
            'deleteProductReview',
            'rateProductReview',
            'rateVendorReview',
            'removeReviewRating'
        ]);
    }
    /**
     * عرض تقييمات منتج معين
     */
    public function getProductReviews(Request $request, $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        
        $query = $product->reviews()->with(['user', 'vendor'])
            ->approved()
            ->orderBy('created_at', 'desc');

        // تصفية حسب التقييم
        if ($request->has('rating') && $request->rating) {
            $query->byRating($request->rating);
        }

        $reviews = $query->paginate(10);

        // تحويل مسارات الصور وإضافة إحصائيات التقييمات المفيدة
        $reviews->getCollection()->transform(function ($review) {
            $review->setAttribute('images_urls', $review->images_urls);
            
            // إضافة إحصائيات التقييمات المفيدة
            $helpfulCount = $review->helpfulVotes()->count();
            $notHelpfulCount = $review->notHelpfulVotes()->count();
            
            $review->setAttribute('helpful_count', $helpfulCount);
            $review->setAttribute('not_helpful_count', $notHelpfulCount);
            
            // إضافة تقييم المستخدم الحالي إذا كان مسجل دخول
            if (Auth::check()) {
                $userRating = ReviewHelpfulness::where('review_id', $review->id)
                    ->where('review_type', 'product')
                    ->where('user_id', Auth::id())
                    ->first();
                $review->setAttribute('user_rating', $userRating ? $userRating->is_helpful : null);
            } else {
                $review->setAttribute('user_rating', null);
            }
            
            return $review;
        });

        return response()->json([
            'success' => true,
            'data' => $reviews->items(), // تحويل paginator إلى array
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'rating' => $product->rating,
                'reviews_count' => $product->reviews_count
            ]
        ]);
    }

    /**
     * عرض تقييمات مورد معين
     */
    public function getVendorReviews(Request $request, $vendorId): JsonResponse
    {
        $vendor = Vendor::findOrFail($vendorId);
        
        $query = $vendor->reviews()->with('user')
            ->approved()
            ->orderBy('created_at', 'desc');

        // تصفية حسب التقييم
        if ($request->has('rating') && $request->rating) {
            $query->byRating($request->rating);
        }

        $reviews = $query->paginate(10);

        // تحويل مسارات الصور وإضافة إحصائيات التقييمات المفيدة
        $reviews->getCollection()->transform(function ($review) {
            $review->setAttribute('images_urls', $review->images_urls);
            
            // إضافة إحصائيات التقييمات المفيدة
            $helpfulCount = $review->helpfulVotes()->count();
            $notHelpfulCount = $review->notHelpfulVotes()->count();
            
            $review->setAttribute('helpful_count', $helpfulCount);
            $review->setAttribute('not_helpful_count', $notHelpfulCount);
            
            // إضافة تقييم المستخدم الحالي إذا كان مسجل دخول
            if (Auth::check()) {
                $userRating = ReviewHelpfulness::where('review_id', $review->id)
                    ->where('review_type', 'vendor')
                    ->where('user_id', Auth::id())
                    ->first();
                $review->setAttribute('user_rating', $userRating ? $userRating->is_helpful : null);
            } else {
                $review->setAttribute('user_rating', null);
            }
            
            return $review;
        });

        return response()->json([
            'success' => true,
            'data' => $reviews->items(), // تحويل paginator إلى array
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'rating' => $vendor->rating,
                'reviews_count' => $vendor->reviews_count
            ]
        ]);
    }

    /**
     * إضافة تقييم لمنتج
     */
    public function storeProductReview(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|in:0,1,true,false',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // تحويل is_anonymous إلى boolean
        $isAnonymous = filter_var($request->is_anonymous, FILTER_VALIDATE_BOOLEAN);

        // التحقق من عدم وجود تقييم سابق
        $existingReview = ProductReview::where('product_id', $request->product_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المنتج مسبقاً'
            ], 400);
        }

        // رفع الصور
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        // إنشاء التقييم
        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'vendor_id' => $product->vendor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_anonymous' => $isAnonymous,
            'images' => $images,
            'is_approved' => true // يمكن تغيير هذا حسب إعدادات النظام
        ]);

        // تحديث تقييم المنتج
        $product->updateRating($request->rating);

        // تحديث تقييم المورد
        $product->vendor->updateRating($request->rating);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التقييم بنجاح',
            'data' => $review->load(['user', 'vendor'])
        ], 201);
    }

    /**
     * إضافة تقييم لمورد
     */
    public function storeVendorReview(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|in:0,1,true,false',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $vendor = Vendor::findOrFail($request->vendor_id);

        // تحويل is_anonymous إلى boolean
        $isAnonymous = filter_var($request->is_anonymous, FILTER_VALIDATE_BOOLEAN);

        // التحقق من عدم وجود تقييم سابق
        $existingReview = VendorReview::where('vendor_id', $request->vendor_id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المورد مسبقاً'
            ], 400);
        }

        // رفع الصور
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        // إنشاء التقييم
        $review = VendorReview::create([
            'vendor_id' => $request->vendor_id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_anonymous' => $isAnonymous,
            'images' => $images,
            'is_approved' => true // يمكن تغيير هذا حسب إعدادات النظام
        ]);

        // تحديث تقييم المورد
        $vendor->updateRating($request->rating);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التقييم بنجاح',
            'data' => $review->load('user')
        ], 201);
    }

    /**
     * تحديث تقييم منتج
     */
    public function updateProductReview(Request $request, $id): JsonResponse
    {
        $review = ProductReview::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|in:0,1,true,false',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldRating = $review->rating;
        $newRating = $request->rating;

        // تحويل is_anonymous إلى boolean
        $isAnonymous = filter_var($request->is_anonymous, FILTER_VALIDATE_BOOLEAN);

        // رفع الصور الجديدة
        $images = $review->images ?? [];
        if ($request->hasFile('images')) {
            // حذف الصور القديمة
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        // تحديث التقييم
        $review->update([
            'rating' => $newRating,
            'comment' => $request->comment,
            'is_anonymous' => $isAnonymous,
            'images' => $images
        ]);

        // تحديث تقييم المنتج
        $product = $review->product;
        $product->recalculateRating($oldRating, $newRating);

        // تحديث تقييم المورد
        $vendor = $review->vendor;
        $vendor->recalculateRating($oldRating, $newRating);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث التقييم بنجاح',
            'data' => $review->load(['user', 'vendor'])
        ]);
    }

    /**
     * حذف تقييم منتج
     */
    public function deleteProductReview($id): JsonResponse
    {
        $review = ProductReview::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $oldRating = $review->rating;
        $product = $review->product;
        $vendor = $review->vendor;

        // حذف الصور
        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $review->delete();

        // تحديث تقييم المنتج
        $product->recalculateRatingAfterDelete($oldRating);

        // تحديث تقييم المورد
        $vendor->recalculateRatingAfterDelete($oldRating);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التقييم بنجاح'
        ]);
    }

    /**
     * إحصائيات التقييمات لمنتج
     */
    public function getProductReviewStats($productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        
        $stats = [
            'total_reviews' => $product->reviews_count ?? 0,
            'average_rating' => $product->rating ?? 0,
            'rating_distribution' => [
                5 => $product->reviews()->byRating(5)->count(),
                4 => $product->reviews()->byRating(4)->count(),
                3 => $product->reviews()->byRating(3)->count(),
                2 => $product->reviews()->byRating(2)->count(),
                1 => $product->reviews()->byRating(1)->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * تقييم تقييم منتج (مفيد/غير مفيد)
     */
    public function rateProductReview(Request $request, $reviewId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'is_helpful' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $review = ProductReview::findOrFail($reviewId);

        // التحقق من عدم وجود تقييم سابق
        $existingRating = ReviewHelpfulness::where('review_id', $reviewId)
            ->where('review_type', 'product')
            ->where('user_id', $user->id)
            ->first();

        if ($existingRating) {
            // تحديث التقييم الموجود
            $existingRating->update(['is_helpful' => $request->is_helpful]);
            $message = $request->is_helpful ? 'تم تحديث التقييم إلى مفيد' : 'تم تحديث التقييم إلى غير مفيد';
        } else {
            // إنشاء تقييم جديد
            ReviewHelpfulness::create([
                'review_id' => $reviewId,
                'review_type' => 'product',
                'user_id' => $user->id,
                'is_helpful' => $request->is_helpful
            ]);
            $message = $request->is_helpful ? 'تم تقييم التقييم كمفيد' : 'تم تقييم التقييم كغير مفيد';
        }

        // جلب إحصائيات التقييم
        $helpfulCount = $review->helpfulVotes()->count();
        $notHelpfulCount = $review->notHelpfulVotes()->count();
        $userRating = ReviewHelpfulness::where('review_id', $reviewId)
            ->where('review_type', 'product')
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'helpful_count' => $helpfulCount,
                'not_helpful_count' => $notHelpfulCount,
                'user_rating' => $userRating ? $userRating->is_helpful : null
            ]
        ]);
    }

    /**
     * تقييم تقييم مورد (مفيد/غير مفيد)
     */
    public function rateVendorReview(Request $request, $reviewId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'is_helpful' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $review = VendorReview::findOrFail($reviewId);

        // التحقق من عدم وجود تقييم سابق
        $existingRating = ReviewHelpfulness::where('review_id', $reviewId)
            ->where('review_type', 'vendor')
            ->where('user_id', $user->id)
            ->first();

        if ($existingRating) {
            // تحديث التقييم الموجود
            $existingRating->update(['is_helpful' => $request->is_helpful]);
            $message = $request->is_helpful ? 'تم تحديث التقييم إلى مفيد' : 'تم تحديث التقييم إلى غير مفيد';
        } else {
            // إنشاء تقييم جديد
            ReviewHelpfulness::create([
                'review_id' => $reviewId,
                'review_type' => 'vendor',
                'user_id' => $user->id,
                'is_helpful' => $request->is_helpful
            ]);
            $message = $request->is_helpful ? 'تم تقييم التقييم كمفيد' : 'تم تقييم التقييم كغير مفيد';
        }

        // جلب إحصائيات التقييم
        $helpfulCount = $review->helpfulVotes()->count();
        $notHelpfulCount = $review->notHelpfulVotes()->count();
        $userRating = ReviewHelpfulness::where('review_id', $reviewId)
            ->where('review_type', 'vendor')
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'helpful_count' => $helpfulCount,
                'not_helpful_count' => $notHelpfulCount,
                'user_rating' => $userRating ? $userRating->is_helpful : null
            ]
        ]);
    }

    /**
     * إزالة تقييم التقييم
     */
    public function removeReviewRating(Request $request, $reviewId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review_type' => 'required|in:product,vendor'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $reviewType = $request->review_type;

        // البحث عن التقييم وحذفه
        $rating = ReviewHelpfulness::where('review_id', $reviewId)
            ->where('review_type', $reviewType)
            ->where('user_id', $user->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على التقييم'
            ], 404);
        }

        $rating->delete();

        // جلب إحصائيات التقييم المحدثة
        if ($reviewType === 'product') {
            $review = ProductReview::findOrFail($reviewId);
            $helpfulCount = $review->helpfulVotes()->count();
            $notHelpfulCount = $review->notHelpfulVotes()->count();
        } else {
            $review = VendorReview::findOrFail($reviewId);
            $helpfulCount = $review->helpfulVotes()->count();
            $notHelpfulCount = $review->notHelpfulVotes()->count();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التقييم بنجاح',
            'data' => [
                'helpful_count' => $helpfulCount,
                'not_helpful_count' => $notHelpfulCount,
                'user_rating' => null
            ]
        ]);
    }
}
