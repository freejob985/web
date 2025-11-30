<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vendor;
use App\Models\VendorSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VendorSettingsController extends Controller
{
    /**
     * Get vendor settings
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $vendor = Vendor::with('settings')->find($vendorId);
            if (!$vendor) {
                return response()->json(['success' => false, 'message' => 'Vendor not found'], 404);
            }

            // Get or create vendor settings
            $settings = $vendor->settings;
            if (!$settings) {
                $settings = VendorSettings::create(['vendor_id' => $vendorId]);
            }

            // Get vendor settings
            $settingsData = [
                'store' => [
                    'store_name' => $settings->store_name ?? $vendor->business_name,
                    'store_description' => $settings->store_description ?? $vendor->description,
                    'store_logo' => $settings->store_logo ?? $vendor->logo,
                    'store_cover_image' => $settings->store_cover_image ?? $vendor->cover_image,
                    'store_phone' => $settings->store_phone ?? $vendor->phone,
                    'store_email' => $settings->store_email ?? $vendor->email,
                    'store_address' => $settings->store_address ?? $vendor->address,
                    'store_city' => $settings->store_city ?? $vendor->city,
                    'store_governorate' => $settings->store_governorate ?? $vendor->governorate,
                ],
                'business' => [
                    'business_license' => $settings->business_license,
                    'tax_number' => $settings->tax_number ?? $vendor->tax_number,
                    'commercial_record' => $settings->commercial_record ?? $vendor->commercial_record,
                    'business_category' => $settings->business_category,
                ],
                'delivery' => [
                    'delivery_enabled' => $settings->delivery_enabled,
                    'delivery_fee' => $settings->delivery_fee,
                    'free_delivery_threshold' => $settings->free_delivery_threshold,
                    'delivery_time_min' => $settings->delivery_time_min,
                    'delivery_time_max' => $settings->delivery_time_max,
                    'delivery_areas' => $settings->delivery_areas,
                ],
                'payment' => [
                    'cash_on_delivery' => $settings->cash_on_delivery,
                    'card_payment' => $settings->card_payment,
                    'knet_payment' => $settings->knet_payment,
                    'wallet_payment' => $settings->wallet_payment,
                ],
                'notifications' => [
                    'email_notifications' => $settings->email_notifications,
                    'sms_notifications' => $settings->sms_notifications,
                    'order_notifications' => $settings->order_notifications,
                    'stock_notifications' => $settings->stock_notifications,
                    'promotion_notifications' => $settings->promotion_notifications,
                ],
                'policies' => [
                    'return_policy' => $settings->return_policy,
                    'shipping_policy' => $settings->shipping_policy,
                    'privacy_policy' => $settings->privacy_policy,
                    'return_period_days' => $settings->return_period_days,
                ],
                'social' => [
                    'facebook_url' => $settings->facebook_url,
                    'instagram_url' => $settings->instagram_url,
                    'twitter_url' => $settings->twitter_url,
                    'linkedin_url' => $settings->linkedin_url,
                    'website_url' => $settings->website_url,
                ],
                'working_hours' => [
                    'working_hours' => $settings->working_hours,
                    'is_24_hours' => $settings->is_24_hours,
                ],
                'orders' => [
                    'auto_accept_orders' => $settings->auto_accept_orders,
                    'require_order_confirmation' => $settings->require_order_confirmation,
                    'max_order_amount' => $settings->max_order_amount,
                    'min_order_amount' => $settings->min_order_amount,
                ],
                'status' => [
                    'is_active' => $settings->is_active,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $settingsData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update vendor settings
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $vendor = Vendor::with('settings')->find($vendorId);
            if (!$vendor) {
                return response()->json(['success' => false, 'message' => 'Vendor not found'], 404);
            }

            // Get or create vendor settings
            $settings = $vendor->settings;
            if (!$settings) {
                $settings = VendorSettings::create(['vendor_id' => $vendorId]);
            }

            $validator = Validator::make($request->all(), [
                'store.store_name' => 'sometimes|string|max:255',
                'store.store_description' => 'sometimes|string|max:1000',
                'store.store_phone' => 'sometimes|string|max:20',
                'store.store_email' => 'sometimes|email|max:255',
                'store.store_address' => 'sometimes|string|max:500',
                'store.store_city' => 'sometimes|string|max:100',
                'store.store_governorate' => 'sometimes|string|max:100',
                'store.store_logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'store.store_cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'business.business_license' => 'sometimes|string|max:100',
                'business.tax_number' => 'sometimes|string|max:100',
                'business.commercial_record' => 'sometimes|string|max:100',
                'business.business_category' => 'sometimes|string|max:100',
                'delivery.delivery_enabled' => 'sometimes|boolean',
                'delivery.delivery_fee' => 'sometimes|numeric|min:0',
                'delivery.free_delivery_threshold' => 'sometimes|numeric|min:0',
                'delivery.delivery_time_min' => 'sometimes|integer|min:1',
                'delivery.delivery_time_max' => 'sometimes|integer|min:1',
                'delivery.delivery_areas' => 'sometimes|array',
                'payment.cash_on_delivery' => 'sometimes|boolean',
                'payment.card_payment' => 'sometimes|boolean',
                'payment.knet_payment' => 'sometimes|boolean',
                'payment.wallet_payment' => 'sometimes|boolean',
                'notifications.email_notifications' => 'sometimes|boolean',
                'notifications.sms_notifications' => 'sometimes|boolean',
                'notifications.order_notifications' => 'sometimes|boolean',
                'notifications.stock_notifications' => 'sometimes|boolean',
                'notifications.promotion_notifications' => 'sometimes|boolean',
                'policies.return_policy' => 'sometimes|string|max:2000',
                'policies.shipping_policy' => 'sometimes|string|max:2000',
                'policies.privacy_policy' => 'sometimes|string|max:2000',
                'policies.return_period_days' => 'sometimes|integer|min:1|max:30',
                'social.facebook_url' => 'sometimes|url|max:255',
                'social.instagram_url' => 'sometimes|url|max:255',
                'social.twitter_url' => 'sometimes|url|max:255',
                'social.linkedin_url' => 'sometimes|url|max:255',
                'social.website_url' => 'sometimes|url|max:255',
                'working_hours.working_hours' => 'sometimes|array',
                'working_hours.is_24_hours' => 'sometimes|boolean',
                'orders.auto_accept_orders' => 'sometimes|boolean',
                'orders.require_order_confirmation' => 'sometimes|boolean',
                'orders.max_order_amount' => 'sometimes|integer|min:0',
                'orders.min_order_amount' => 'sometimes|integer|min:0',
                'status.is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            // Process each section
            foreach ($request->all() as $section => $data) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        // استخدام المفتاح كما هو
                        $updateData[$key] = $value;
                    }
                }
            }

            // إضافة معلومات إضافية للتصحيح
            \Log::info('Update data:', $updateData);

            // Update settings
            $settings->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $settings->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update specific setting group
     */
    public function updateGroup(Request $request, string $group): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $vendor = Vendor::with('settings')->find($vendorId);
            if (!$vendor) {
                return response()->json(['success' => false, 'message' => 'Vendor not found'], 404);
            }

            // Get or create vendor settings
            $settings = $vendor->settings;
            if (!$settings) {
                $settings = VendorSettings::create(['vendor_id' => $vendorId]);
            }

            $allowedGroups = ['store', 'business', 'delivery', 'payment', 'notifications', 'policies', 'social', 'working_hours', 'orders', 'status'];
            if (!in_array($group, $allowedGroups)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid setting group'
                ], 400);
            }

            $data = $request->all();
            
            // Handle file uploads for store group
            if ($group === 'store') {
                if ($request->hasFile('store_logo')) {
                    $logoPath = $request->file('store_logo')->store('vendor-logos', 'public');
                    $data['store_logo'] = Storage::url($logoPath);
                }

                if ($request->hasFile('store_cover_image')) {
                    $coverPath = $request->file('store_cover_image')->store('vendor-covers', 'public');
                    $data['store_cover_image'] = Storage::url($coverPath);
                }
            }

            $settings->update($data);

            return response()->json([
                'success' => true,
                'message' => ucfirst($group) . ' settings updated successfully',
                'data' => $settings->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating ' . $group . ' settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor ID from authenticated user
     */
    private function getVendorId(Request $request): ?int
    {
        // Get from authenticated vendor (middleware ensures this exists)
        $vendor = $request->user('vendor');
        if ($vendor) {
            return $vendor->id;
        }

        return null;
    }
}
