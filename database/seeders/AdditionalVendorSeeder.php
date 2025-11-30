<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\BusinessCategory;
use App\Models\Governorate;
use App\Models\City;

class AdditionalVendorSeeder extends Seeder
{
    public function run()
    {
        // Get existing data
        $businessCategories = BusinessCategory::all();
        $governorates = Governorate::all();
        $cities = City::all();

        if ($businessCategories->isEmpty() || $governorates->isEmpty() || $cities->isEmpty()) {
            $this->command->warn('يجب تشغيل seeders أخرى أولاً (BusinessCategory, KuwaitData)');
            return;
        }

        // Get default values
        $defaultBusinessCategory = $businessCategories->first();
        $defaultGovernorate = $governorates->first();
        $defaultCity = $cities->first();

        $vendors = [
            [
                'name' => 'مزرعة الخير للخضروات الطازجة',
                'name_ar' => 'مزرعة الخير للخضروات الطازجة',
                'email' => 'kheir.farm@example.com',
                'password' => bcrypt('password123'),
                'phone' => '+965 50 111 1111',
                'description' => 'نقدم أفضل الخضروات والفواكه الطازجة من مزارعنا المحلية',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'مزرعة الخير، الفحيحيل، محافظة الأحمدي',
                'rating' => 4.8,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة البحر الأحمر للأسماك',
                'name_ar' => 'مؤسسة البحر الأحمر للأسماك',
                'email' => 'redsea.fish@example.com',
                'phone' => '+965 50 222 2222',
                'description' => 'أسماك طازجة من البحر الأحمر يومياً',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'سوق السمك، مدينة الكويت',
                'rating' => 4.9,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مخبز الأصالة',
                'name_ar' => 'مخبز الأصالة',
                'email' => 'asalah.bakery@example.com',
                'phone' => '+965 50 333 3333',
                'description' => 'مخبوزات طازجة يومياً بأفضل المكونات',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'شارع سالم المبارك، حولي',
                'rating' => 4.7,
                'is_active' => true,
                'is_featured' => false,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة اللحوم الطازجة',
                'name_ar' => 'مؤسسة اللحوم الطازجة',
                'email' => 'fresh.meat@example.com',
                'phone' => '+965 50 444 4444',
                'description' => 'لحوم ودواجن طازجة من أفضل المزارع',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'سوق اللحوم، الجهراء',
                'rating' => 4.6,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة الألبان الذهبية',
                'name_ar' => 'مؤسسة الألبان الذهبية',
                'email' => 'golden.dairy@example.com',
                'phone' => '+965 50 555 5555',
                'description' => 'منتجات ألبان طازجة ومتنوعة',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'منطقة الدسمة، مدينة الكويت',
                'rating' => 4.8,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة التوابل الشرقية',
                'name_ar' => 'مؤسسة التوابل الشرقية',
                'email' => 'eastern.spices@example.com',
                'phone' => '+965 50 666 6666',
                'description' => 'توابل وأعشاب طبيعية من أفضل المصادر',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'سوق التوابل، مدينة الكويت',
                'rating' => 4.5,
                'is_active' => true,
                'is_featured' => false,
                'is_fresh' => false
            ],
            [
                'name' => 'مؤسسة المكسرات الذهبية',
                'name_ar' => 'مؤسسة المكسرات الذهبية',
                'email' => 'golden.nuts@example.com',
                'phone' => '+965 50 777 7777',
                'description' => 'مكسرات وبذور طازجة ومتنوعة',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'شارع المنصورية، حولي',
                'rating' => 4.7,
                'is_active' => true,
                'is_featured' => false,
                'is_fresh' => false
            ],
            [
                'name' => 'مؤسسة المشروبات الطبيعية',
                'name_ar' => 'مؤسسة المشروبات الطبيعية',
                'email' => 'natural.drinks@example.com',
                'phone' => '+965 50 888 8888',
                'description' => 'عصائر ومشروبات طبيعية 100%',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'منطقة الفحيحيل، محافظة الأحمدي',
                'rating' => 4.9,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة المنتجات العضوية',
                'name_ar' => 'مؤسسة المنتجات العضوية',
                'email' => 'organic.products@example.com',
                'phone' => '+965 50 999 9999',
                'description' => 'منتجات عضوية معتمدة وخالية من المواد الكيميائية',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'مزرعة عضوية، الجهراء',
                'rating' => 4.8,
                'is_active' => true,
                'is_featured' => true,
                'is_fresh' => true
            ],
            [
                'name' => 'مؤسسة المنتجات المحلية',
                'name_ar' => 'مؤسسة المنتجات المحلية',
                'email' => 'local.products@example.com',
                'phone' => '+965 50 000 0000',
                'description' => 'منتجات محلية كويتية أصيلة',
                'business_category_id' => $defaultBusinessCategory->id,
                'governorate_id' => $defaultGovernorate->id,
                'city_id' => $defaultCity->id,
                'address' => 'المنطقة التجارية، مدينة الكويت',
                'rating' => 4.6,
                'is_active' => true,
                'is_featured' => false,
                'is_fresh' => false
            ]
        ];

        foreach ($vendors as $vendorData) {
            // Add password if not exists
            if (!isset($vendorData['password'])) {
                $vendorData['password'] = bcrypt('password123');
            }
            
            Vendor::firstOrCreate(
                ['email' => $vendorData['email']],
                $vendorData
            );
        }

        $this->command->info('تم إنشاء ' . count($vendors) . ' بائع إضافي بنجاح!');
    }
}