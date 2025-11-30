<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = [
            'general' => 'إعدادات الموقع العامة',
            'email' => 'إعدادات البريد الإلكتروني',
            'payment' => 'إعدادات الدفع',
            'seo' => 'إعدادات السيو',
            'design' => 'إعدادات التصميم',
            'developer' => 'وضع المطور'
        ];

        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('groups', 'settings'));
    }

    public function show($group)
    {
        $groupLabels = [
            'general' => 'إعدادات الموقع العامة',
            'email' => 'إعدادات البريد الإلكتروني',
            'payment' => 'إعدادات الدفع',
            'seo' => 'إعدادات السيو',
            'design' => 'إعدادات التصميم',
            'developer' => 'وضع المطور'
        ];

        if (!array_key_exists($group, $groupLabels)) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'مجموعة الإعدادات غير موجودة');
        }

        $settings = Setting::byGroup($group)->get();
        
        return view('admin.settings.show', compact('group', 'groupLabels', 'settings'));
    }

    public function update(Request $request, $group)
    {
        $settings = Setting::byGroup($group)->get();
        
        // Handle file uploads first
        foreach ($request->allFiles() as $key => $file) {
            if ($file->isValid()) {
                $setting = $settings->where('key', $key)->first();
                if ($setting && $setting->type === 'file') {
                    try {
                        // Delete old file if exists
                        if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                            \Storage::disk('public')->delete($setting->value);
                        }
                        
                        // Store new file
                        $path = $file->store('settings', 'public');
                        
                        // Update the setting with the new file path
                        $setting->update(['value' => $path]);
                        
                        \Log::info("File uploaded successfully for setting {$key}: {$path}");
                    } catch (\Exception $e) {
                        \Log::error("File upload failed for setting {$key}: " . $e->getMessage());
                        return redirect()->route('admin.settings.show', $group)
                            ->with('error', 'فشل في رفع الملف: ' . $e->getMessage());
                    }
                }
            } else {
                \Log::warning("Invalid file upload for setting {$key}");
            }
        }
        
        // Handle other settings (skip file inputs)
        foreach ($settings as $setting) {
            $key = $setting->key;
            if ($request->has($key) && $setting->type !== 'file') {
                $value = $request->input($key);
                $setting->setValue($value);
            }
        }

        return redirect()->route('admin.settings.show', $group)
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function create()
    {
        $groups = [
            'general' => 'إعدادات الموقع العامة',
            'email' => 'إعدادات البريد الإلكتروني',
            'payment' => 'إعدادات الدفع',
            'seo' => 'إعدادات السيو',
            'design' => 'إعدادات التصميم',
            'developer' => 'وضع المطور'
        ];

        $types = [
            'string' => 'نص',
            'text' => 'نص طويل',
            'number' => 'رقم',
            'boolean' => 'نعم/لا',
            'json' => 'JSON',
            'file' => 'ملف'
        ];

        return view('admin.settings.create', compact('groups', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable',
            'type' => 'required|in:string,text,number,boolean,json,file',
            'group' => 'required|in:general,email,payment,seo,design,developer',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'is_public' => 'boolean'
        ]);

        Setting::create($data);

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم إنشاء الإعداد بنجاح');
    }

    public function edit(Setting $setting)
    {
        $groups = [
            'general' => 'إعدادات الموقع العامة',
            'email' => 'إعدادات البريد الإلكتروني',
            'payment' => 'إعدادات الدفع',
            'seo' => 'إعدادات السيو',
            'design' => 'إعدادات التصميم',
            'developer' => 'وضع المطور'
        ];

        $types = [
            'string' => 'نص',
            'text' => 'نص طويل',
            'number' => 'رقم',
            'boolean' => 'نعم/لا',
            'json' => 'JSON',
            'file' => 'ملف'
        ];

        return view('admin.settings.edit', compact('setting', 'groups', 'types'));
    }

    public function updateSetting(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'value' => 'nullable',
            'type' => 'required|in:string,text,number,boolean,json,file',
            'group' => 'required|in:general,email,payment,seo,design,developer',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'is_public' => 'boolean'
        ]);

        $setting->update($data);

        return redirect()->route('admin.settings.show', $setting->group)
            ->with('success', 'تم تحديث الإعداد بنجاح');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.show', $setting->group)
            ->with('success', 'تم حذف الإعداد بنجاح');
    }

    public function reset($group)
    {
        // Reset to default values (you can define default values here)
        $defaults = $this->getDefaultSettings();
        
        if (isset($defaults[$group])) {
            foreach ($defaults[$group] as $key => $value) {
                Setting::set($key, $value['value'], $value['type'], $group);
            }
        }

        return redirect()->route('admin.settings.show', $group)
            ->with('success', 'تم إعادة تعيين الإعدادات إلى القيم الافتراضية');
    }

    private function getDefaultSettings()
    {
        return [
            'general' => [
                'site_name' => [
                    'value' => 'إنقب',
                    'type' => 'string',
                    'label' => 'اسم الموقع'
                ],
                'site_logo' => [
                    'value' => '',
                    'type' => 'file',
                    'label' => 'شعار الموقع'
                ],
                'site_description' => [
                    'value' => 'منصة التسوق الإلكتروني الرائدة في الكويت',
                    'type' => 'text',
                    'label' => 'وصف الموقع'
                ],
                'site_url' => [
                    'value' => 'http://localhost:5174',
                    'type' => 'string',
                    'label' => 'رابط الموقع'
                ],
                'contact_phone' => [
                    'value' => '+965 1234 5678',
                    'type' => 'string',
                    'label' => 'رقم الهاتف'
                ],
                'contact_email' => [
                    'value' => 'info@engeb.com',
                    'type' => 'string',
                    'label' => 'البريد الإلكتروني'
                ],
                'currency' => [
                    'value' => 'KWD',
                    'type' => 'string',
                    'label' => 'العملة'
                ],
                'timezone' => [
                    'value' => 'Asia/Kuwait',
                    'type' => 'string',
                    'label' => 'المنطقة الزمنية'
                ]
            ],
            'email' => [
                'mail_driver' => [
                    'value' => 'smtp',
                    'type' => 'string',
                    'label' => 'نوع البريد'
                ],
                'mail_host' => [
                    'value' => 'smtp.gmail.com',
                    'type' => 'string',
                    'label' => 'خادم البريد'
                ],
                'mail_port' => [
                    'value' => '587',
                    'type' => 'number',
                    'label' => 'منفذ البريد'
                ],
                'mail_username' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'اسم المستخدم'
                ],
                'mail_password' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'كلمة المرور'
                ],
                'mail_encryption' => [
                    'value' => 'tls',
                    'type' => 'string',
                    'label' => 'التشفير'
                ],
                'mail_from_address' => [
                    'value' => 'noreply@engeb.com',
                    'type' => 'string',
                    'label' => 'عنوان المرسل'
                ],
                'mail_from_name' => [
                    'value' => 'إنقب',
                    'type' => 'string',
                    'label' => 'اسم المرسل'
                ]
            ],
            'payment' => [
                'payment_gateway' => [
                    'value' => 'knet',
                    'type' => 'string',
                    'label' => 'بوابة الدفع'
                ],
                'knet_merchant_id' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'معرف التاجر KNET'
                ],
                'knet_merchant_password' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'كلمة مرور KNET'
                ],
                'stripe_public_key' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'مفتاح Stripe العام'
                ],
                'stripe_secret_key' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'مفتاح Stripe السري'
                ],
                'payment_test_mode' => [
                    'value' => '1',
                    'type' => 'boolean',
                    'label' => 'وضع الاختبار'
                ]
            ],
            'seo' => [
                'meta_title' => [
                    'value' => 'إنقب - التسوق الإلكتروني في الكويت',
                    'type' => 'string',
                    'label' => 'عنوان الصفحة الرئيسية'
                ],
                'meta_description' => [
                    'value' => 'تسوق بسهولة وأمان مع إنقب، منصة التسوق الإلكتروني الرائدة في الكويت',
                    'type' => 'text',
                    'label' => 'وصف الصفحة الرئيسية'
                ],
                'meta_keywords' => [
                    'value' => 'تسوق، إلكتروني، الكويت، إنقب، منتجات، توصيل',
                    'type' => 'string',
                    'label' => 'الكلمات المفتاحية'
                ],
                'google_analytics' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'كود Google Analytics'
                ],
                'google_tag_manager' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'كود Google Tag Manager'
                ],
                'facebook_pixel' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'كود Facebook Pixel'
                ]
            ],
            'design' => [
                'primary_color' => [
                    'value' => '#3B82F6',
                    'type' => 'string',
                    'label' => 'اللون الأساسي'
                ],
                'secondary_color' => [
                    'value' => '#10B981',
                    'type' => 'string',
                    'label' => 'اللون الثانوي'
                ],
                'logo_url' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'رابط الشعار'
                ],
                'favicon_url' => [
                    'value' => '',
                    'type' => 'string',
                    'label' => 'رابط الأيقونة'
                ],
                'theme_mode' => [
                    'value' => 'light',
                    'type' => 'string',
                    'label' => 'وضع التصميم'
                ]
            ],
            'developer' => [
                'debug_mode' => [
                    'value' => '0',
                    'type' => 'boolean',
                    'label' => 'وضع التطوير'
                ],
                'maintenance_mode' => [
                    'value' => '0',
                    'type' => 'boolean',
                    'label' => 'وضع الصيانة'
                ],
                'api_rate_limit' => [
                    'value' => '100',
                    'type' => 'number',
                    'label' => 'حد معدل API'
                ],
                'cache_duration' => [
                    'value' => '3600',
                    'type' => 'number',
                    'label' => 'مدة التخزين المؤقت (ثانية)'
                ]
            ]
        ];
    }
}