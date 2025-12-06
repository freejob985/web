<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PdfController;
//تطويرات في ملفات
Route::prefix('v1')->group(function () {
    Route::get('/ping', fn () => response()->json(['message' => 'ok']))->name('api.ping');

    
    // Search endpoints
    Route::get('/search', [\App\Http\Controllers\Api\SearchController::class, 'search']);

    // New category and location endpoints
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/categories/main', [\App\Http\Controllers\Api\CategoryController::class, 'mainCategories']);
    Route::get('/categories/supermarket', [\App\Http\Controllers\Api\CategoryController::class, 'supermarketCategories']);
    Route::get('/categories/{category}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);
    Route::get('/categories/{category}/subcategories', [\App\Http\Controllers\Api\CategoryController::class, 'subcategories']);
    Route::get('/categories/{category}/products', [\App\Http\Controllers\Api\CategoryController::class, 'products']);
    
    Route::get('/subcategories', [\App\Http\Controllers\Api\SubcategoryController::class, 'index']);
    Route::get('/subcategories/{subcategory}', [\App\Http\Controllers\Api\SubcategoryController::class, 'show']);
    
    Route::get('/governorates', [\App\Http\Controllers\Api\GovernorateController::class, 'index']);
    Route::get('/governorates/{governorate}', [\App\Http\Controllers\Api\GovernorateController::class, 'show']);
    Route::get('/governorates/{governorate}/cities', [\App\Http\Controllers\Api\GovernorateController::class, 'cities']);
    
    Route::get('/cities', [\App\Http\Controllers\Api\CityController::class, 'index']);
    Route::get('/cities/{city}', [\App\Http\Controllers\Api\CityController::class, 'show']);
    
    // Simplified location endpoints for frontend
    Route::get('/locations/governorates', [\App\Http\Controllers\Api\LocationController::class, 'getGovernorates']);
    Route::get('/locations/cities', [\App\Http\Controllers\Api\LocationController::class, 'getCities']);
    
    // Business categories endpoints
    Route::get('/business-categories', [\App\Http\Controllers\Api\BusinessCategoryController::class, 'index']);
    Route::get('/business-categories/{id}', [\App\Http\Controllers\Api\BusinessCategoryController::class, 'show']);

    // Brand endpoints
    Route::get('/brands', [\App\Http\Controllers\Api\BrandController::class, 'index']);
    Route::get('/brands/{id}', [\App\Http\Controllers\Api\BrandController::class, 'show']);
    Route::get('/brands/{id}/products', [\App\Http\Controllers\Api\BrandController::class, 'products']);
    
    // Contact methods endpoints
    Route::get('/contact-methods', [\App\Http\Controllers\Api\ContactMethodController::class, 'index']);

    // Vendor endpoints
    Route::get('/vendors', [\App\Http\Controllers\Api\VendorController::class, 'index']);
    Route::get('/vendors/featured', [\App\Http\Controllers\Api\VendorController::class, 'featured']);
    Route::get('/vendors/{id}', [\App\Http\Controllers\Api\VendorController::class, 'show']);
    Route::get('/vendors/{id}/products', [\App\Http\Controllers\Api\VendorController::class, 'products']);

    // Product endpoints
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/fresh', [ProductController::class, 'fresh']);
    Route::get('/products/offers', [ProductController::class, 'offers']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Review endpoints (public)
    Route::get('/products/{productId}/reviews', [\App\Http\Controllers\ReviewController::class, 'getProductReviews']);
    Route::get('/vendors/{vendorId}/reviews', [\App\Http\Controllers\ReviewController::class, 'getVendorReviews']);
    Route::get('/products/{productId}/reviews/stats', [\App\Http\Controllers\ReviewController::class, 'getProductReviewStats']);

    // Offer endpoints
    Route::get('/offers', [\App\Http\Controllers\Api\OfferController::class, 'index']);
    Route::get('/offers/featured', [\App\Http\Controllers\Api\OfferController::class, 'featured']);
    Route::get('/offers/flash-sale', [\App\Http\Controllers\Api\OfferController::class, 'flashSale']);
    Route::get('/offers/search', [\App\Http\Controllers\Api\OfferController::class, 'search']);
    Route::get('/offers/{offer}', [\App\Http\Controllers\Api\OfferController::class, 'show']);
    Route::get('/offers/category/{category}', [\App\Http\Controllers\Api\OfferController::class, 'byCategory']);

    // Offer Category endpoints
    Route::get('/offer-categories', [\App\Http\Controllers\Api\OfferCategoryController::class, 'index']);
    Route::get('/offer-categories/{offerCategory}', [\App\Http\Controllers\Api\OfferCategoryController::class, 'show']);
    Route::get('/offer-categories/{offerCategory}/offers', [\App\Http\Controllers\Api\OfferCategoryController::class, 'offers']);

    // Session-based routes (without CSRF for API)
    Route::middleware([
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \App\Http\Middleware\StartSessionWithoutCsrf::class
    ])->group(function () {
        // Cart endpoints (session-based)
        Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
        Route::get('/cart/count', [\App\Http\Controllers\Api\CartController::class, 'count']);
        Route::post('/cart', [\App\Http\Controllers\Api\CartController::class, 'store']);
        Route::put('/cart/{productId}', [\App\Http\Controllers\Api\CartController::class, 'update']);
        Route::delete('/cart/{productId}', [\App\Http\Controllers\Api\CartController::class, 'destroy']);
        Route::delete('/cart', [\App\Http\Controllers\Api\CartController::class, 'clear']);
        
        // Cart coupon endpoints
        Route::post('/cart/coupon/apply', [\App\Http\Controllers\Api\CartController::class, 'applyCoupon']);
        Route::delete('/cart/coupon/remove', [\App\Http\Controllers\Api\CartController::class, 'removeCoupon']);

        // Checkout endpoints
        Route::get('/checkout/data', [\App\Http\Controllers\Api\CheckoutController::class, 'getCheckoutData']);
        Route::post('/checkout', [\App\Http\Controllers\Api\CheckoutController::class, 'store']);

        // Wishlist (session-based)
        Route::get('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
        Route::post('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'store']);
        Route::delete('/wishlist/{productId}', [\App\Http\Controllers\Api\WishlistController::class, 'destroy']);

        // Orders (recent + track + details + confirmation + invoice)
        Route::get('/orders/recent', [\App\Http\Controllers\Api\OrderController::class, 'recent']);
        Route::get('/orders/track/{orderNumber}', [\App\Http\Controllers\Api\OrderController::class, 'track']);
        Route::get('/orders/confirmation/{orderNumber}', [\App\Http\Controllers\Api\OrderController::class, 'confirmation']);
        Route::get('/orders/{id}/details', [\App\Http\Controllers\Api\OrderController::class, 'details']);
        Route::get('/orders/invoice/{orderNumber}', [\App\Http\Controllers\Api\OrderController::class, 'downloadInvoice']);
        Route::get('/invoices/{orderNumber}/pdf', [InvoiceController::class, 'generatePdf']);
        Route::get('/invoices/{orderNumber}/html', [InvoiceController::class, 'generateHtml']);
        Route::get('/pdf/{orderNumber}/invoice', [PdfController::class, 'generateInvoice']);
        Route::get('/pdf/{orderNumber}/print', [PdfController::class, 'generatePdfPrint']);

        // Auth (session)
        Route::post('/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
        Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
        Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('/auth/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
        Route::post('/auth/verify-token', [\App\Http\Controllers\Api\AuthController::class, 'verifyToken']);
        Route::put('/auth/profile', [\App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
        Route::post('/auth/change-password', [\App\Http\Controllers\UserController::class, 'changePassword']);

        // Review endpoints (protected - requires auth)
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/reviews/products', [\App\Http\Controllers\ReviewController::class, 'storeProductReview']);
            Route::post('/reviews/vendors', [\App\Http\Controllers\ReviewController::class, 'storeVendorReview']);
            Route::put('/reviews/products/{id}', [\App\Http\Controllers\ReviewController::class, 'updateProductReview']);
            Route::delete('/reviews/products/{id}', [\App\Http\Controllers\ReviewController::class, 'deleteProductReview']);

            // Review helpfulness endpoints (protected)
            Route::post('/reviews/products/{id}/rate', [\App\Http\Controllers\ReviewController::class, 'rateProductReview']);
            Route::post('/reviews/vendors/{id}/rate', [\App\Http\Controllers\ReviewController::class, 'rateVendorReview']);
            Route::delete('/reviews/{id}/rating', [\App\Http\Controllers\ReviewController::class, 'removeReviewRating']);
        });

        // Vendor Auth (session)
        Route::post('/vendor/auth/register', [\App\Http\Controllers\Api\VendorAuthController::class, 'register']);
        Route::post('/vendor/auth/login', [\App\Http\Controllers\Api\VendorAuthController::class, 'login']);
        Route::post('/vendor/auth/logout', [\App\Http\Controllers\Api\VendorAuthController::class, 'logout']);
        Route::get('/vendor/auth/me', [\App\Http\Controllers\Api\VendorAuthController::class, 'me']);
        Route::put('/vendor/auth/profile', [\App\Http\Controllers\Api\VendorAuthController::class, 'updateProfile']);
        Route::post('/vendor/auth/validate-token', [\App\Http\Controllers\Api\VendorAuthController::class, 'validateToken']);

        // Vendor Dashboard APIs (protected with token authentication)
        Route::middleware('vendor.token')->group(function () {
            Route::get('/vendor/dashboard/stats', [\App\Http\Controllers\Api\VendorDashboardController::class, 'stats']);
            Route::get('/vendor/dashboard/recent-orders', [\App\Http\Controllers\Api\VendorDashboardController::class, 'recentOrders']);
            Route::get('/vendor/dashboard/top-products', [\App\Http\Controllers\Api\VendorDashboardController::class, 'topProducts']);
            Route::get('/vendor/dashboard/notifications', [\App\Http\Controllers\Api\VendorDashboardController::class, 'notifications']);
            Route::post('/vendor/dashboard/notifications/mark-read', [\App\Http\Controllers\Api\VendorDashboardController::class, 'markNotificationsRead']);
            Route::get('/vendor/dashboard/notifications/unread-count', [\App\Http\Controllers\Api\VendorDashboardController::class, 'getUnreadCount']);

            // Vendor Products APIs
            Route::get('/vendor/products', [\App\Http\Controllers\Api\VendorProductController::class, 'index']);
            Route::post('/vendor/products', [\App\Http\Controllers\Api\VendorProductController::class, 'store']);
            Route::get('/vendor/products/{id}', [\App\Http\Controllers\Api\VendorProductController::class, 'show']);
            Route::put('/vendor/products/{id}', [\App\Http\Controllers\Api\VendorProductController::class, 'update']);
            Route::delete('/vendor/products/{id}', [\App\Http\Controllers\Api\VendorProductController::class, 'destroy']);

            // Vendor Orders APIs
            Route::get('/vendor/orders', [\App\Http\Controllers\Api\VendorOrderController::class, 'index']);
            Route::get('/vendor/orders/{id}', [\App\Http\Controllers\Api\VendorOrderController::class, 'show']);
            Route::put('/vendor/orders/{id}/status', [\App\Http\Controllers\Api\VendorOrderController::class, 'updateStatus']);

            // Vendor Reports APIs
            Route::get('/vendor/reports', [\App\Http\Controllers\Api\VendorReportController::class, 'index']);
            Route::get('/vendor/reports/sales', [\App\Http\Controllers\Api\VendorReportController::class, 'sales']);
            Route::get('/vendor/reports/products', [\App\Http\Controllers\Api\VendorReportController::class, 'products']);
            Route::get('/vendor/reports/revenue', [\App\Http\Controllers\Api\VendorReportController::class, 'revenue']);

            // Vendor Settings APIs
            Route::get('/vendor/settings', [\App\Http\Controllers\Api\VendorSettingsController::class, 'index']);
            Route::put('/vendor/settings', [\App\Http\Controllers\Api\VendorSettingsController::class, 'update']);
        });

        // Addresses
        Route::get('/addresses', [\App\Http\Controllers\Api\AddressesController::class, 'index']);
        Route::post('/addresses', [\App\Http\Controllers\Api\AddressesController::class, 'store']);
        Route::put('/addresses/{id}', [\App\Http\Controllers\Api\AddressesController::class, 'update']);
        
        // Notification Settings
        Route::get('/notification-settings', [\App\Http\Controllers\Api\NotificationSettingsController::class, 'index']);
        Route::put('/notification-settings', [\App\Http\Controllers\Api\NotificationSettingsController::class, 'update']);
        Route::get('/notification-preferences', [\App\Http\Controllers\Api\NotificationSettingsController::class, 'preferences']);
        Route::delete('/addresses/{id}', [\App\Http\Controllers\Api\AddressesController::class, 'destroy']);
        Route::post('/addresses/{id}/default', [\App\Http\Controllers\Api\AddressesController::class, 'setDefault']);

        // Demo seeding (session used to set recent orders)
        Route::match(['GET','POST'], '/demo/seed', [\App\Http\Controllers\Api\DemoController::class, 'seed']);
    });

    // Contact form endpoints (public)
    Route::post('/contact', [\App\Http\Controllers\Api\ContactController::class, 'store']);
    Route::get('/contact/stats', [\App\Http\Controllers\Api\ContactController::class, 'getStats']);
    
// Additional search endpoints (public)
Route::get('/search/products', [\App\Http\Controllers\Api\SearchController::class, 'searchProducts']);
Route::get('/search/filters', [\App\Http\Controllers\Api\SearchController::class, 'getSearchFilters']);
Route::get('/search/popular', [\App\Http\Controllers\Api\SearchController::class, 'getPopularSearches']);
Route::get('/search/autocomplete', [\App\Http\Controllers\Api\SearchController::class, 'autocomplete']);

// Admin notification endpoints (public for frontend access)
Route::get('/admin/notifications/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount']);
Route::get('/admin/notifications/recent', [\App\Http\Controllers\Admin\NotificationController::class, 'getRecent']);
Route::get('/admin/notifications/stats', [\App\Http\Controllers\Admin\NotificationController::class, 'getStats']);

// FAQ endpoints (public)
Route::get('/faqs', [\App\Http\Controllers\Api\FaqController::class, 'index']);
Route::get('/faqs/categories', [\App\Http\Controllers\Api\FaqController::class, 'categories']);
Route::get('/faqs/{id}', [\App\Http\Controllers\Api\FaqController::class, 'show']);

// Coupon endpoints (public)
Route::get('/coupons', [\App\Http\Controllers\Api\CouponController::class, 'index']);
Route::post('/coupons/validate', [\App\Http\Controllers\Api\CouponController::class, 'validateCoupon']);
Route::post('/coupons/apply', [\App\Http\Controllers\Api\CouponController::class, 'apply']);

// Newsletter endpoints (public)
Route::post('/newsletter/subscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'subscribe']);
Route::post('/newsletter/unsubscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'unsubscribe']);
Route::post('/newsletter/resubscribe', [\App\Http\Controllers\Api\NewsletterController::class, 'resubscribe']);
Route::get('/newsletter/check', [\App\Http\Controllers\Api\NewsletterController::class, 'checkSubscription']);
Route::get('/newsletter/stats', [\App\Http\Controllers\Api\NewsletterController::class, 'stats']);

// Settings endpoints (public)
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::get('/settings/general', [\App\Http\Controllers\Api\SettingsController::class, 'general']);
Route::get('/settings/email', [\App\Http\Controllers\Api\SettingsController::class, 'email']);
Route::get('/settings/payment', [\App\Http\Controllers\Api\SettingsController::class, 'payment']);
Route::get('/settings/seo', [\App\Http\Controllers\Api\SettingsController::class, 'seo']);
Route::get('/settings/design', [\App\Http\Controllers\Api\SettingsController::class, 'design']);
Route::get('/settings/developer', [\App\Http\Controllers\Api\SettingsController::class, 'developer']);
Route::get('/settings/{group}', [\App\Http\Controllers\Api\SettingsController::class, 'getGroup']);
Route::get('/settings/key/{key}', [\App\Http\Controllers\Api\SettingsController::class, 'get']);

// Support Channels endpoints (public)
Route::get('/support-channels', [\App\Http\Controllers\Api\SupportChannelController::class, 'index']);
Route::get('/support-channels/{supportChannel}', [\App\Http\Controllers\Api\SupportChannelController::class, 'show']);

    // About Page endpoints (public)
    Route::get('/about', [\App\Http\Controllers\Api\AboutPageController::class, 'index']);
    Route::get('/about/section/{section}', [\App\Http\Controllers\Api\AboutPageController::class, 'getBySection']);
    Route::get('/about/hero', [\App\Http\Controllers\Api\AboutPageController::class, 'getHero']);
    Route::get('/about/story', [\App\Http\Controllers\Api\AboutPageController::class, 'getStory']);
    Route::get('/about/values', [\App\Http\Controllers\Api\AboutPageController::class, 'getValues']);
    Route::get('/about/statistics', [\App\Http\Controllers\Api\AboutPageController::class, 'getStatistics']);
    Route::get('/about/team', [\App\Http\Controllers\Api\AboutPageController::class, 'getTeam']);
    Route::get('/about/mission', [\App\Http\Controllers\Api\AboutPageController::class, 'getMission']);

    // Slider endpoints (public)
    Route::get('/sliders', [\App\Http\Controllers\Api\SliderController::class, 'index']);
    Route::get('/sliders/{slider}', [\App\Http\Controllers\Api\SliderController::class, 'show']);

    // Static Pages endpoints (public)
    Route::get('/static-pages', [\App\Http\Controllers\Api\StaticPageController::class, 'index']);
    Route::get('/static-pages/{slug}', [\App\Http\Controllers\Api\StaticPageController::class, 'show']);
    Route::get('/static-pages/fixed', [\App\Http\Controllers\Api\StaticPageController::class, 'fixed']);
    Route::get('/static-pages/editable', [\App\Http\Controllers\Api\StaticPageController::class, 'editable']);
    Route::get('/terms', [\App\Http\Controllers\Api\StaticPageController::class, 'terms']);
    Route::get('/privacy', [\App\Http\Controllers\Api\StaticPageController::class, 'privacy']);
    Route::get('/refund', [\App\Http\Controllers\Api\StaticPageController::class, 'refund']);

    // Salla Delivery Chat Routes مع إشعارات Firebase
    Route::prefix('salla-delivery/chat')->group(function () {
        // إرسال رسالة جديدة مع إشعار Firebase للمستلم
        Route::post('/send/{order_id}', [\App\Http\Controllers\Api\SallaDeliveryChatController::class, 'sendMessage']);
        
        // الحصول على جميع رسائل طلب معين
        Route::get('/messages/{order_id}', [\App\Http\Controllers\Api\SallaDeliveryChatController::class, 'getMessages']);
        
        // تحديد الرسالة كمقروءة
        Route::post('/mark-read/{message_id}', [\App\Http\Controllers\Api\SallaDeliveryChatController::class, 'markAsRead']);
        
        // الحصول على عدد الرسائل غير المقروءة
        Route::get('/unread-count/{order_id}/{recipient_type}', [\App\Http\Controllers\Api\SallaDeliveryChatController::class, 'getUnreadCount']);
        
        // تحديث Firebase token للمستخدم/السائق
        Route::post('/update-fcm-token', [\App\Http\Controllers\Api\SallaDeliveryChatController::class, 'updateFcmToken']);
    });
});
