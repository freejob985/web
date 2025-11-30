<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationSettingsController extends Controller
{
    /**
     * Get user notification settings
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مسجل الدخول'
                ], 401);
            }

            // Get notification settings from user preferences or create default
            $settings = $user->notification_settings ?? [
                'email_notifications' => true,
                'sms_notifications' => false,
                'order_updates' => true,
                'promotions' => true,
                'newsletter' => false
            ];

            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب إعدادات الإشعارات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user notification settings
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مسجل الدخول'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'email_notifications' => 'boolean',
                'sms_notifications' => 'boolean',
                'order_updates' => 'boolean',
                'promotions' => 'boolean',
                'newsletter' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $settings = [
                'email_notifications' => $request->boolean('email_notifications'),
                'sms_notifications' => $request->boolean('sms_notifications'),
                'order_updates' => $request->boolean('order_updates'),
                'promotions' => $request->boolean('promotions'),
                'newsletter' => $request->boolean('newsletter')
            ];

            // Update user notification settings
            $user->notification_settings = $settings;
            $user->save();

            // If newsletter is enabled, subscribe user to newsletter
            if ($settings['newsletter']) {
                $this->subscribeToNewsletter($user);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ إعدادات الإشعارات بنجاح',
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في حفظ إعدادات الإشعارات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscribe user to newsletter
     */
    private function subscribeToNewsletter($user): void
    {
        try {
            // Check if user is already subscribed
            $existingSubscription = \App\Models\Newsletter::where('email', $user->email)->first();
            
            if (!$existingSubscription) {
                \App\Models\Newsletter::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'status' => 'active',
                    'subscribed_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the main request
            \Log::error('Failed to subscribe user to newsletter: ' . $e->getMessage());
        }
    }

    /**
     * Get notification preferences for specific types
     */
    public function preferences(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم غير مسجل الدخول'
                ], 401);
            }

            $preferences = [
                'email' => [
                    'enabled' => $user->notification_settings['email_notifications'] ?? true,
                    'order_updates' => $user->notification_settings['order_updates'] ?? true,
                    'promotions' => $user->notification_settings['promotions'] ?? true,
                    'newsletter' => $user->notification_settings['newsletter'] ?? false
                ],
                'sms' => [
                    'enabled' => $user->notification_settings['sms_notifications'] ?? false,
                    'order_updates' => $user->notification_settings['order_updates'] ?? true,
                    'promotions' => $user->notification_settings['promotions'] ?? true
                ]
            ];

            return response()->json([
                'success' => true,
                'preferences' => $preferences
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في جلب تفضيلات الإشعارات',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
