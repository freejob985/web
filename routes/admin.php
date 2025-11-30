<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ErrorLogController;

Route::name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
        Route::get('/products/import', [ProductController::class, 'showImport'])->name('products.import');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.submit');
        Route::get('/products/template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
        
        Route::resource('faqs', FaqController::class);
        Route::resource('static-pages', \App\Http\Controllers\Admin\StaticPageController::class);
        Route::post('/static-pages/{staticPage}/toggle-status', [\App\Http\Controllers\Admin\StaticPageController::class, 'toggleStatus'])->name('static-pages.toggle-status');
        Route::resource('business-categories', \App\Http\Controllers\Admin\BusinessCategoryController::class);
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/pending', [\App\Http\Controllers\Admin\OrderController::class, 'pending'])->name('orders.pending');
        Route::get('/orders/completed', [\App\Http\Controllers\Admin\OrderController::class, 'completed'])->name('orders.completed');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/pdf', [\App\Http\Controllers\Admin\OrderController::class, 'exportPdf'])->name('orders.pdf');
        Route::post('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');

        Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
        
        // Offer Management routes
        Route::resource('offer-categories', \App\Http\Controllers\Admin\OfferCategoryController::class);
        Route::post('/offer-categories/{offerCategory}/toggle-status', [\App\Http\Controllers\Admin\OfferCategoryController::class, 'toggleStatus'])->name('offer-categories.toggle-status');
        
        Route::resource('offers', \App\Http\Controllers\Admin\OfferController::class);
        Route::post('/offers/{offer}/toggle-status', [\App\Http\Controllers\Admin\OfferController::class, 'toggleStatus'])->name('offers.toggle-status');
        Route::post('/offers/{offer}/toggle-popular', [\App\Http\Controllers\Admin\OfferController::class, 'togglePopular'])->name('offers.toggle-popular');
        Route::post('/offers/{offer}/toggle-flash-sale', [\App\Http\Controllers\Admin\OfferController::class, 'toggleFlashSale'])->name('offers.toggle-flash-sale');
        
        // Users routes - specific routes must come before resource routes
        Route::get('/users/admins', [\App\Http\Controllers\Admin\UserController::class, 'admins'])->name('users.admins');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index','show']);
        
        // New sections
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('subcategories', \App\Http\Controllers\Admin\SubcategoryController::class);
        Route::resource('governorates', \App\Http\Controllers\Admin\GovernorateController::class);
        Route::resource('cities', \App\Http\Controllers\Admin\CityController::class);
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
        Route::post('/coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        
        // Contact Methods Management
        Route::resource('contact-methods', \App\Http\Controllers\Admin\ContactMethodController::class);
        
        // About Page Management
        Route::resource('about-pages', \App\Http\Controllers\Admin\AboutPageController::class);
        Route::post('/about-pages/{aboutPage}/toggle-status', [\App\Http\Controllers\Admin\AboutPageController::class, 'toggleStatus'])->name('about-pages.toggle-status');
        Route::get('/about-pages/section/{section}', [\App\Http\Controllers\Admin\AboutPageController::class, 'showSection'])->name('about-pages.section');
        Route::get('/about-pages/api/section/{section}', [\App\Http\Controllers\Admin\AboutPageController::class, 'getBySection'])->name('about-pages.api.section');
        
        // Error Log routes
        Route::get('/errors', [ErrorLogController::class, 'index'])->name('errors.index');
        Route::get('/errors/{id}', [ErrorLogController::class, 'show'])->name('errors.show');
        Route::delete('/errors/clear', [ErrorLogController::class, 'clear'])->name('errors.clear');
        Route::get('/errors/download', [ErrorLogController::class, 'download'])->name('errors.download');
        Route::get('/errors/stats', [ErrorLogController::class, 'getStats'])->name('errors.stats');
        
        // Newsletter routes
        Route::get('/newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
        Route::delete('/newsletters/{newsletter}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
        Route::post('/newsletters/{newsletter}/unsubscribe', [\App\Http\Controllers\Admin\NewsletterController::class, 'unsubscribe'])->name('newsletters.unsubscribe');
        Route::post('/newsletters/{newsletter}/resubscribe', [\App\Http\Controllers\Admin\NewsletterController::class, 'resubscribe'])->name('newsletters.resubscribe');
        Route::get('/newsletters/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class, 'campaigns'])->name('newsletters.campaigns');
        Route::get('/newsletters/campaigns/create', [\App\Http\Controllers\Admin\NewsletterController::class, 'createCampaign'])->name('newsletters.campaigns.create');
        Route::post('/newsletters/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class, 'storeCampaign'])->name('newsletters.campaigns.store');
        Route::get('/newsletters/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class, 'showCampaign'])->name('newsletters.campaigns.show');
        Route::post('/newsletters/campaigns/{campaign}/send', [\App\Http\Controllers\Admin\NewsletterController::class, 'sendCampaign'])->name('newsletters.campaigns.send');
        Route::get('/newsletters/stats', [\App\Http\Controllers\Admin\NewsletterController::class, 'stats'])->name('newsletters.stats');
        
        // Settings routes
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/{group}', [\App\Http\Controllers\Admin\SettingsController::class, 'show'])->name('settings.show');
        Route::put('/settings/{group}', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/create', [\App\Http\Controllers\Admin\SettingsController::class, 'create'])->name('settings.create');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('settings.store');
        Route::get('/settings/{setting}/edit', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings/{setting}/update', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSetting'])->name('settings.update-setting');
        Route::delete('/settings/{setting}', [\App\Http\Controllers\Admin\SettingsController::class, 'destroy'])->name('settings.destroy');
        Route::post('/settings/{group}/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'reset'])->name('settings.reset');
        
        // System Maintenance routes
        Route::get('/system/maintenance', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'index'])->name('system.maintenance');
        Route::post('/system/clear-all-cache', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearAllCache'])->name('system.clear-all-cache');
        Route::post('/system/clear-cache', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearApplicationCache'])->name('system.clear-cache');
        Route::post('/system/clear-config', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearConfigCache'])->name('system.clear-config');
        Route::post('/system/clear-routes', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearRouteCache'])->name('system.clear-routes');
        Route::post('/system/clear-views', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearViewCache'])->name('system.clear-views');
        Route::post('/system/clear-logs', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearLogs'])->name('system.clear-logs');
        Route::post('/system/clear-sessions', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'clearSessions'])->name('system.clear-sessions');
        Route::post('/system/optimize', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'optimizeSystem'])->name('system.optimize');
        Route::get('/system/logs/{filename}', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'viewLog'])->name('system.view-log');
        Route::get('/system/logs/{filename}/download', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'downloadLog'])->name('system.download-log');
        Route::delete('/system/logs/{filename}', [\App\Http\Controllers\Admin\SystemMaintenanceController::class, 'deleteLog'])->name('system.delete-log');
        
        // Contact messages routes - specific routes must come before resource routes
        Route::get('/contact-messages/read', [\App\Http\Controllers\Admin\ContactMessageController::class, 'read'])->name('contact-messages.read');
        Route::get('/contact-messages/unread', [\App\Http\Controllers\Admin\ContactMessageController::class, 'unread'])->name('contact-messages.unread');
        Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
        
        // Contact message actions
        Route::post('/contact-messages/{contactMessage}/mark-read', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead']);
        Route::post('/contact-messages/{contactMessage}/mark-replied', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markAsReplied']);
        Route::post('/contact-messages/{contactMessage}/mark-closed', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markAsClosed']);
        Route::get('/contact-messages/unread-count', [\App\Http\Controllers\Admin\ContactMessageController::class, 'getUnreadCount']);
        Route::get('/contact-messages/recent', [\App\Http\Controllers\Admin\ContactMessageController::class, 'getRecentMessages']);
        
        // Notifications routes - specific routes must come before resource routes
        Route::get('/notifications/read', [\App\Http\Controllers\Admin\NotificationController::class, 'read'])->name('notifications.read');
        Route::get('/notifications/unread', [\App\Http\Controllers\Admin\NotificationController::class, 'unread'])->name('notifications.unread');
        Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)->only(['index', 'show', 'destroy']);
        Route::post('/notifications/{notification}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead']);
        Route::post('/notifications/{notification}/mark-unread', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsUnread']);
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead']);
        Route::get('/notifications/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::get('/notifications/recent', [\App\Http\Controllers\Admin\NotificationController::class, 'getRecent'])->name('notifications.recent');
        Route::get('/notifications/stats', [\App\Http\Controllers\Admin\NotificationController::class, 'getStats'])->name('notifications.stats');
        
        // Support Channels routes
        Route::resource('support-channels', \App\Http\Controllers\Admin\SupportChannelController::class);
        Route::post('/support-channels/{supportChannel}/toggle-status', [\App\Http\Controllers\Admin\SupportChannelController::class, 'toggleStatus'])->name('support-channels.toggle-status');
        
        // Slider routes
        Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
        Route::post('/sliders/{slider}/toggle', [\App\Http\Controllers\Admin\SliderController::class, 'toggle'])->name('sliders.toggle');
        
        // Review Management routes
        Route::get('/reviews/products', [\App\Http\Controllers\Admin\ReviewController::class, 'productReviews'])->name('reviews.products');
        Route::get('/reviews/vendors', [\App\Http\Controllers\Admin\ReviewController::class, 'vendorReviews'])->name('reviews.vendors');
        Route::get('/reviews/stats', [\App\Http\Controllers\Admin\ReviewController::class, 'stats'])->name('reviews.stats');
        
        // Product Review Actions
        Route::post('/reviews/products/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approveProductReview'])->name('reviews.products.approve');
        Route::post('/reviews/products/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'rejectProductReview'])->name('reviews.products.reject');
        Route::delete('/reviews/products/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'deleteProductReview'])->name('reviews.products.delete');
        
        // Vendor Review Actions
        Route::post('/reviews/vendors/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approveVendorReview'])->name('reviews.vendors.approve');
        Route::post('/reviews/vendors/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'rejectVendorReview'])->name('reviews.vendors.reject');
        Route::delete('/reviews/vendors/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'deleteVendorReview'])->name('reviews.vendors.delete');
        
        // API routes for dynamic loading
        Route::get('/governorates/{governorate}/cities', [\App\Http\Controllers\Admin\GovernorateController::class, 'getCities']);
        Route::get('/categories/{category}/subcategories', [\App\Http\Controllers\Admin\CategoryController::class, 'getSubcategories']);
    });
});