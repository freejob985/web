<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\BusinessCategory;
use App\Models\Governorate;
use App\Models\City;

class CategoryTestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test business category
        $businessCategory = BusinessCategory::firstOrCreate([
            'name' => 'تسوق عام',
            'slug' => 'general-shopping'
        ], [
            'description' => 'تسوق عام ومتنوع',
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Create test governorate and city
        $governorate = Governorate::firstOrCreate([
            'name_ar' => 'محافظة الكويت',
            'name_en' => 'Kuwait Governorate',
            'code' => 'KU'
        ]);

        $city = City::firstOrCreate([
            'name_ar' => 'مدينة الكويت',
            'name_en' => 'Kuwait City',
            'code' => 'KWC',
            'governorate_id' => $governorate->id
        ]);

        // Create test vendor
        $vendor = Vendor::firstOrCreate([
            'email' => 'test@vendor.com'
        ], [
            'name' => 'مورد تجريبي',
            'name_ar' => 'مورد تجريبي',
            'phone' => '123456789',
            'business_category_id' => $businessCategory->id,
            'governorate_id' => $governorate->id,
            'city_id' => $city->id,
            'is_active' => true
        ]);

        // Create categories
        $categories = [
            [
                'name_ar' => 'خضروات وفواكه',
                'name_en' => 'Fruits & Vegetables',
                'slug' => 'fruits-vegetables',
                'description_ar' => 'أفضل الخضروات والفواكه الطازجة',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name_ar' => 'منتجات الألبان',
                'name_en' => 'Dairy Products',
                'slug' => 'dairy',
                'description_ar' => 'منتجات الألبان والأجبان الطازجة',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name_ar' => 'لحوم ودواجن',
                'name_en' => 'Meat & Poultry',
                'slug' => 'meat-poultry',
                'description_ar' => 'لحوم ودواجن طازجة وعالية الجودة',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name_ar' => 'مخبوزات',
                'name_en' => 'Bakery',
                'slug' => 'bakery',
                'description_ar' => 'مخبوزات طازجة ومتنوعة',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name_ar' => 'مجمدات',
                'name_en' => 'Frozen Foods',
                'slug' => 'frozen',
                'description_ar' => 'منتجات مجمدة عالية الجودة',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name_ar' => 'منظفات',
                'name_en' => 'Cleaning Products',
                'slug' => 'cleaning',
                'description_ar' => 'منتجات التنظيف والعناية بالمنزل',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name_ar' => 'منتجات الأطفال',
                'name_en' => 'Baby Care',
                'slug' => 'baby-care',
                'description_ar' => 'مستلزمات الأطفال والعناية بهم',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name_ar' => 'العناية الشخصية',
                'name_en' => 'Personal Care',
                'slug' => 'personal-care',
                'description_ar' => 'منتجات العناية الشخصية والصحة',
                'is_active' => true,
                'sort_order' => 8
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Create test products for each category
            $products = [
                [
                    'name' => 'منتج تجريبي 1 - ' . $category->name_ar,
                    'description' => 'منتج تجريبي عالي الجودة',
                    'price' => 10.000,
                    'original_price' => 15.000,
                    'stock' => 100,
                    'sku' => 'TEST' . $category->id . '001',
                    'image' => 'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                    'category_id' => $category->id,
                    'vendor_id' => $vendor->id,
                    'is_active' => true,
                    'is_fresh' => true,
                    'rating' => 4.5
                ],
                [
                    'name' => 'منتج تجريبي 2 - ' . $category->name_ar,
                    'description' => 'منتج تجريبي مميز',
                    'price' => 20.000,
                    'original_price' => 25.000,
                    'stock' => 50,
                    'sku' => 'TEST' . $category->id . '002',
                    'image' => 'https://images.pexels.com/photos/18258533/pexels-photo-18258533.jpeg',
                    'category_id' => $category->id,
                    'vendor_id' => $vendor->id,
                    'is_active' => true,
                    'is_fresh' => false,
                    'rating' => 4.8
                ]
            ];

            foreach ($products as $productData) {
                Product::firstOrCreate(
                    ['sku' => $productData['sku']],
                    $productData
                );
            }
        }

        $this->command->info('Test categories and products created successfully!');
    }
}
