<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // إعدادات عامة للموقع
            [
                'key' => 'site_name',
                'value' => 'إنجب',
                'type' => 'string',
                'group' => 'general',
                'label' => 'اسم الموقع',
                'description' => 'اسم الموقع الذي يظهر في الهيدر والفوتر',
                'is_public' => true,
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'general',
                'label' => 'شعار الموقع',
                'description' => 'رفع شعار الموقع (PNG, JPG, SVG)',
                'is_public' => true,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+965 50 123 4567',
                'type' => 'string',
                'group' => 'general',
                'label' => 'رقم الهاتف',
                'description' => 'رقم الهاتف للتواصل',
                'is_public' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@engeb.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'البريد الإلكتروني',
                'description' => 'البريد الإلكتروني للتواصل',
                'is_public' => true,
            ],
            [
                'key' => 'contact_address',
                'value' => 'مدينة الكويت، دولة الكويت',
                'type' => 'string',
                'group' => 'general',
                'label' => 'العنوان',
                'description' => 'عنوان الشركة',
                'is_public' => true,
            ],
            [
                'key' => 'google_map_embed',
                'value' => null,
                'type' => 'text',
                'group' => 'general',
                'label' => 'تضمين خريطة جوجل',
                'description' => 'كود تضمين خريطة جوجل ليظهر في صفحة التواصل',
                'is_public' => true,
            ],
            [
                'key' => 'facebook_url',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'label' => 'رابط فيسبوك',
                'description' => 'رابط صفحة فيسبوك',
                'is_public' => true,
            ],
            [
                'key' => 'twitter_url',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'label' => 'رابط تويتر',
                'description' => 'رابط صفحة تويتر',
                'is_public' => true,
            ],
            [
                'key' => 'instagram_url',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'label' => 'رابط إنستغرام',
                'description' => 'رابط صفحة إنستغرام',
                'is_public' => true,
            ],
            [
                'key' => 'linkedin_url',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'label' => 'رابط لينكد إن',
                'description' => 'رابط صفحة لينكد إن',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}