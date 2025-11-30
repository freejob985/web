<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Get all settings grouped by category.
     */
    public function index()
    {
        $settings = Cache::remember('settings_all', 60, function () {
            return [
                'general' => $this->getGeneralSettings(),
                'email' => $this->getEmailSettings(),
                'payment' => $this->getPaymentSettings(),
                'seo' => $this->getSeoSettings(),
                'design' => $this->getDesignSettings(),
                'developer' => $this->getDeveloperSettings(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get general settings.
     */
    public function general()
    {
        $settings = Cache::remember('settings_general', 60, function () {
            return $this->getGeneralSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get settings by group.
     */
    public function getGroup($group)
    {
        $method = 'get' . ucfirst($group) . 'Settings';
        
        if (!method_exists($this, $method)) {
            return response()->json([
                'success' => false,
                'message' => 'Settings group not found'
            ], 404);
        }

        $settings = Cache::remember("settings_{$group}", 60, function () use ($method) {
            return $this->$method();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get design settings (including footer logo).
     */
    public function design()
    {
        $settings = Cache::remember('settings_design', 60, function () {
            return $this->getDesignSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get payment settings.
     */
    public function payment()
    {
        $settings = Cache::remember('settings_payment', 60, function () {
            return $this->getPaymentSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get email settings.
     */
    public function email()
    {
        $settings = Cache::remember('settings_email', 60, function () {
            return $this->getEmailSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get SEO settings.
     */
    public function seo()
    {
        $settings = Cache::remember('settings_seo', 60, function () {
            return $this->getSeoSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get developer settings.
     */
    public function developer()
    {
        $settings = Cache::remember('settings_developer', 60, function () {
            return $this->getDeveloperSettings();
        });

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Get specific setting by key.
     */
    public function get($key)
    {
        $value = Cache::remember("setting_{$key}", 60, function () use ($key) {
            return $this->getSettingValue($key);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $value
            ]
        ]);
    }

    /**
     * Get general settings data.
     */
    private function getGeneralSettings()
    {
        $settings = \App\Models\Setting::where('group', 'general')
            ->where('is_public', true)
            ->get()
            ->keyBy('key');

        // Transform image URLs
        $settings->transform(function ($setting) {
            return $setting->appendImageUrls();
        });

        $siteLogo = $settings->get('site_logo')?->value;
        $siteLogoUrl = null;
        
        if ($siteLogo) {
            $siteLogoUrl = asset('storage/' . $siteLogo);
        }

        return [
            'site_name' => $settings->get('site_name')?->value ?? 'إنجب',
            'site_description' => 'منصة التسوق الإلكتروني الرائدة في الكويت',
            'site_logo' => $siteLogo,
            'site_logo_url' => $siteLogoUrl,
            'site_favicon' => 'favicon.ico',
            'site_favicon_url' => asset('favicon.ico'),
            'contact_phone' => $settings->get('contact_phone')?->value ?? '+965 50 123 4567',
            'contact_email' => $settings->get('contact_email')?->value ?? 'info@engeb.com',
            'contact_address' => $settings->get('contact_address')?->value ?? 'مدينة الكويت، دولة الكويت',
            'facebook_url' => $settings->get('facebook_url')?->value,
            'twitter_url' => $settings->get('twitter_url')?->value,
            'instagram_url' => $settings->get('instagram_url')?->value,
            'linkedin_url' => $settings->get('linkedin_url')?->value,
            'google_map_embed' => $settings->get('google_map_embed')?->value,
            'map_latitude' => $settings->get('map_latitude')?->value ?? '29.3759',
            'map_longitude' => $settings->get('map_longitude')?->value ?? '47.9784',
            'map_zoom_level' => $settings->get('map_zoom_level')?->value ?? '13',
            'map_marker_title' => $settings->get('map_marker_title')?->value ?? 'إنجب - منصة التسوق الإلكتروني',
            'map_marker_description' => $settings->get('map_marker_description')?->value ?? 'منصة التسوق الإلكتروني الرائدة في الكويت',
            'map_height' => $settings->get('map_height')?->value ?? '500',
            'map_style' => $settings->get('map_style')?->value ?? 'default',
            'map_enabled' => $settings->get('map_enabled')?->value ?? true,
            'default_language' => 'ar',
            'supported_languages' => ['ar', 'en'],
            'timezone' => 'Asia/Kuwait',
            'currency' => 'KWD',
            'currency_symbol' => 'د.ك',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'items_per_page' => 20,
            'max_upload_size' => '10MB',
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'email_verification_required' => false,
        ];
    }

    /**
     * Get email settings data.
     */
    private function getEmailSettings()
    {
        $settingsQuery = \App\Models\Setting::where('group', 'email')
            ->where('is_public', false);
        $settings = $settingsQuery->get()->keyBy('key');

        return [
            'mail_driver' => $settings->get('mail_driver')?->value ?? 'smtp',
            'mail_host' => $settings->get('mail_host')?->value ?? 'smtp.gmail.com',
            'mail_port' => $settings->get('mail_port')?->value ?? 587,
            'mail_username' => $settings->get('mail_username')?->value ?? 'noreply@engeb.com',
            'mail_encryption' => $settings->get('mail_encryption')?->value ?? 'tls',
            'mail_from_address' => $settings->get('mail_from_address')?->value ?? 'noreply@engeb.com',
            'mail_from_name' => $settings->get('mail_from_name')?->value ?? 'إنجب',
        ];
    }

    /**
     * Get payment settings data.
     */
    private function getPaymentSettings()
    {
        $settingsQuery = \App\Models\Setting::where('group', 'payment');
        $settings = $settingsQuery->get()->keyBy('key');

        return [
            'payment_methods' => $settings->get('payment_methods')?->getValue() ?? ['cash', 'card', 'knet', 'wallet'],
            'knet_enabled' => $settings->get('knet_enabled')?->getValue() ?? false,
            'stripe_enabled' => $settings->get('stripe_enabled')?->getValue() ?? false,
            'delivery_fee' => $settings->get('delivery_fee')?->getValue() ?? 2.0,
            'free_delivery_threshold' => $settings->get('free_delivery_threshold')?->getValue() ?? 25.0,
        ];
    }

    /**
     * Get design settings data.
     */
    private function getDesignSettings()
    {
        $settingsQuery = \App\Models\Setting::where('group', 'design');
        $settings = $settingsQuery->get()->keyBy('key');

        $siteFooterLogo = $settings->get('site_footer_logo')?->value;
        $siteFooterLogoUrl = null;
        
        if ($siteFooterLogo) {
            $siteFooterLogoUrl = asset('storage/' . $siteFooterLogo);
        }

        return [
            'primary_color' => $settings->get('primary_color')?->value ?? '#3B82F6',
            'secondary_color' => $settings->get('secondary_color')?->value ?? '#10B981',
            'accent_color' => $settings->get('accent_color')?->value ?? '#F59E0B',
            'custom_css' => $settings->get('custom_css')?->value ?? '',
            'admin_custom_css' => $settings->get('admin_custom_css')?->value ?? '',
            'theme_mode' => $settings->get('theme_mode')?->value ?? 'light',
            'font_family' => $settings->get('font_family')?->value ?? 'Cairo, sans-serif',
            'site_footer_logo' => $siteFooterLogo,
            'site_footer_logo_url' => $siteFooterLogoUrl,
        ];
    }

    /**
     * Get developer settings data.
     */
    private function getDeveloperSettings()
    {
        $settingsQuery = \App\Models\Setting::where('group', 'developer')
            ->where('is_public', false);
        $settings = $settingsQuery->get()->keyBy('key');

        return [
            'maintenance_mode' => $settings->get('maintenance_mode')?->getValue() ?? false,
            'maintenance_message' => $settings->get('maintenance_message')?->value ?? 'نحن نعمل على تحسين الموقع، سنعود قريباً!',
            'debug_mode' => $settings->get('debug_mode')?->getValue() ?? false,
            'api_rate_limit' => $settings->get('api_rate_limit')?->getValue() ?? 100,
            'cache_duration' => $settings->get('cache_duration')?->getValue() ?? 3600,
            'log_level' => $settings->get('log_level')?->value ?? 'error',
        ];
    }

    /**
     * Get social media settings data.
     */
    private function getSocialSettings()
    {
        return [
            'facebook_url' => 'https://facebook.com/engeb',
            'twitter_url' => 'https://twitter.com/engeb',
            'instagram_url' => 'https://instagram.com/engeb',
            'linkedin_url' => 'https://linkedin.com/company/engeb',
            'youtube_url' => 'https://youtube.com/engeb',
            'tiktok_url' => 'https://tiktok.com/@engeb',
            'whatsapp_number' => '+965501234567',
            'telegram_username' => '@engeb',
        ];
    }

    /**
     * Get SEO settings data.
     */
    private function getSeoSettings()
    {
        return [
            'meta_title' => 'إنجب - منصة التسوق الإلكتروني الرائدة في الكويت',
            'meta_description' => 'اكتشف أفضل المنتجات الغذائية والاستهلاكية من موردين موثوقين في الكويت. تسوق بسهولة وأمان مع إنجب.',
            'meta_keywords' => 'تسوق إلكتروني, كويت, منتجات غذائية, بقالة, توصيل',
            'og_title' => 'إنجب - منصة التسوق الإلكتروني',
            'og_description' => 'منصة التسوق الإلكتروني الرائدة في الكويت',
            'og_image' => asset('storage/og-image.jpg'),
            'twitter_card' => 'summary_large_image',
            'google_analytics_id' => '',
            'google_tag_manager_id' => '',
            'facebook_pixel_id' => '',
        ];
    }

    /**
     * Get specific setting value by key.
     */
    private function getSettingValue($key)
    {
        $allSettings = [
            // General settings
            'site_name' => 'إنجب',
            'site_description' => 'منصة التسوق الإلكتروني الرائدة في الكويت',
            'site_logo' => 'logo.png',
            'site_logo_url' => asset('storage/logo.png'),
            'default_language' => 'ar',
            'timezone' => 'Asia/Kuwait',
            'currency' => 'KWD',
            'currency_symbol' => 'د.ك',
            
            // Contact settings
            'contact_phone' => '+965 50 123 4567',
            'contact_email' => 'info@engeb.com',
            'contact_address' => 'مدينة الكويت، دولة الكويت',
            'working_hours' => '24/7',
            
            // Social settings
            'facebook_url' => 'https://facebook.com/engeb',
            'twitter_url' => 'https://twitter.com/engeb',
            'instagram_url' => 'https://instagram.com/engeb',
            'linkedin_url' => 'https://linkedin.com/company/engeb',
            
            // SEO settings
            'meta_title' => 'إنجب - منصة التسوق الإلكتروني الرائدة في الكويت',
            'meta_description' => 'اكتشف أفضل المنتجات الغذائية والاستهلاكية من موردين موثوقين في الكويت.',
        ];

        return $allSettings[$key] ?? null;
    }
}