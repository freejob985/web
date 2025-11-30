<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Governorate;
use App\Models\City;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Sample product data with realistic Arabic names and descriptions
        $products = [
            // خضروات وفواكه طازجة
            [
                'name' => 'تفاح أحمر طازج - كيلو',
                'description' => 'تفاح أحمر طازج ولذيذ، مستورد من أجود المزارع. غني بالفيتامينات والألياف الطبيعية.',
                'price' => 1.250,
                'original_price' => 1.500,
                'stock' => 150,
                'sku' => 'APPLE-RED-001',
                'image' => 'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                    'https://images.pexels.com/photos/1510392/pexels-photo-1510392.jpeg'
                ],
                'origin' => 'تركيا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'rating' => 4.8,
                'reviews_count' => 156,
                'sales_count' => 342,
                'nutritional_info' => [
                    'calories' => '52 سعرة حرارية لكل 100 جرام',
                    'carbs' => '14 جرام',
                    'fiber' => '2.4 جرام',
                    'vitamin_c' => '4.6 مجم'
                ],
                'expiry_date' => Carbon::now()->addDays(7)
            ],
            [
                'name' => 'خيار طازج محلي - كيلو',
                'description' => 'خيار طازج من المزارع المحلية الكويتية، مقرمش ومنعش، مثالي للسلطات والعصائر.',
                'price' => 0.800,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'CUCUMBER-LOCAL-001',
                'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg'
                ],
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.9,
                'reviews_count' => 89,
                'sales_count' => 234,
                'nutritional_info' => [
                    'calories' => '16 سعرة حرارية لكل 100 جرام',
                    'water' => '95%',
                    'vitamin_k' => '16.4 مكجم'
                ],
                'expiry_date' => Carbon::now()->addDays(5)
            ],
            [
                'name' => 'طماطم كرزية طازجة - 500 جرام',
                'description' => 'طماطم كرزية صغيرة وحلوة المذاق، مثالية للسلطات والوجبات الخفيفة.',
                'price' => 1.200,
                'original_price' => 1.400,
                'stock' => 80,
                'sku' => 'TOMATO-CHERRY-001',
                'image' => 'https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg'
                ],
                'origin' => 'لبنان',
                'weight' => 0.500,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'rating' => 4.6,
                'reviews_count' => 67,
                'sales_count' => 156,
                'nutritional_info' => [
                    'calories' => '18 سعرة حرارية لكل 100 جرام',
                    'lycopene' => 'مضاد أكسدة قوي',
                    'vitamin_c' => '13.7 مجم'
                ],
                'expiry_date' => Carbon::now()->addDays(4)
            ],
            [
                'name' => 'موز طازج - كيلو',
                'description' => 'موز طازج وناضج، غني بالبوتاسيوم والطاقة الطبيعية، مثالي للإفطار والوجبات الخفيفة.',
                'price' => 0.950,
                'original_price' => null,
                'stock' => 120,
                'sku' => 'BANANA-FRESH-001',
                'image' => 'https://images.pexels.com/photos/2872755/pexels-photo-2872755.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/2872755/pexels-photo-2872755.jpeg'
                ],
                'origin' => 'الإكوادور',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.7,
                'reviews_count' => 134,
                'sales_count' => 289,
                'nutritional_info' => [
                    'calories' => '89 سعرة حرارية لكل 100 جرام',
                    'potassium' => '358 مجم',
                    'vitamin_b6' => '0.4 مجم'
                ],
                'expiry_date' => Carbon::now()->addDays(6)
            ],
            [
                'name' => 'جزر طازج - كيلو',
                'description' => 'جزر طازج ومقرمش، غني بفيتامين أ والبيتا كاروتين، مفيد للنظر والصحة العامة.',
                'price' => 0.750,
                'original_price' => null,
                'stock' => 180,
                'sku' => 'CARROT-FRESH-001',
                'image' => 'https://images.pexels.com/photos/3650647/pexels-photo-3650647.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/3650647/pexels-photo-3650647.jpeg'
                ],
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.5,
                'reviews_count' => 78,
                'sales_count' => 167,
                'nutritional_info' => [
                    'calories' => '41 سعرة حرارية لكل 100 جرام',
                    'beta_carotene' => '8285 مكجم',
                    'fiber' => '2.8 جرام'
                ],
                'expiry_date' => Carbon::now()->addDays(10)
            ],

            // منتجات الألبان
            [
                'name' => 'حليب طازج كامل الدسم - لتر',
                'description' => 'حليب طازج كامل الدسم من أجود المزارع المحلية، غني بالكالسيوم والبروتين.',
                'price' => 0.650,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'MILK-FULL-001',
                'image' => 'https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg'
                ],
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'لتر',
                'is_fresh' => true,
                'is_featured' => true,
                'rating' => 4.8,
                'reviews_count' => 245,
                'sales_count' => 567,
                'nutritional_info' => [
                    'calories' => '61 سعرة حرارية لكل 100 مل',
                    'protein' => '3.2 جرام',
                    'calcium' => '113 مجم',
                    'fat' => '3.2 جرام'
                ],
                'expiry_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'جبن أبيض طازج - 250 جرام',
                'description' => 'جبن أبيض طازج وطري، مصنوع من حليب طبيعي 100%، مثالي للإفطار والسندويشات.',
                'price' => 1.800,
                'original_price' => 2.000,
                'stock' => 60,
                'sku' => 'CHEESE-WHITE-001',
                'image' => 'https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg'
                ],
                'origin' => 'الدنمارك',
                'weight' => 0.250,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.4,
                'reviews_count' => 92,
                'sales_count' => 134,
                'nutritional_info' => [
                    'calories' => '264 سعرة حرارية لكل 100 جرام',
                    'protein' => '25 جرام',
                    'calcium' => '710 مجم'
                ],
                'expiry_date' => Carbon::now()->addDays(7)
            ],

            // اللحوم والدواجن
            [
                'name' => 'دجاج طازج محلي - كيلو',
                'description' => 'دجاج طازج من المزارع المحلية، مربى بطريقة طبيعية وصحية، لحم طري ولذيذ.',
                'price' => 3.500,
                'original_price' => null,
                'stock' => 45,
                'sku' => 'CHICKEN-LOCAL-001',
                'image' => 'https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg'
                ],
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'rating' => 4.9,
                'reviews_count' => 167,
                'sales_count' => 234,
                'nutritional_info' => [
                    'calories' => '165 سعرة حرارية لكل 100 جرام',
                    'protein' => '31 جرام',
                    'fat' => '3.6 جرام'
                ],
                'expiry_date' => Carbon::now()->addDays(2)
            ],
            [
                'name' => 'لحم غنم طازج - كيلو',
                'description' => 'لحم غنم طازج ومتبل، من أجود أنواع الأغنام المحلية، مثالي للشواء والطبخ.',
                'price' => 8.500,
                'original_price' => 9.000,
                'stock' => 25,
                'sku' => 'LAMB-FRESH-001',
                'image' => 'https://images.pexels.com/photos/3688/food-dinner-lunch-unhealthy.jpg',
                'images' => [
                    'https://images.pexels.com/photos/3688/food-dinner-lunch-unhealthy.jpg'
                ],
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'rating' => 4.7,
                'reviews_count' => 89,
                'sales_count' => 123,
                'nutritional_info' => [
                    'calories' => '294 سعرة حرارية لكل 100 جرام',
                    'protein' => '25 جرام',
                    'iron' => '1.9 مجم'
                ],
                'expiry_date' => Carbon::now()->addDays(2)
            ],

            // المخبوزات
            [
                'name' => 'خبز عربي طازج - 5 أرغفة',
                'description' => 'خبز عربي طازج ومخبوز يومياً، طري ولذيذ، مثالي لجميع الوجبات.',
                'price' => 0.500,
                'original_price' => null,
                'stock' => 300,
                'sku' => 'BREAD-ARABIC-001',
                'image' => 'https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg'
                ],
                'origin' => 'الكويت',
                'weight' => 0.400,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.6,
                'reviews_count' => 234,
                'sales_count' => 678,
                'nutritional_info' => [
                    'calories' => '275 سعرة حرارية لكل 100 جرام',
                    'carbs' => '56 جرام',
                    'protein' => '9 جرام'
                ],
                'expiry_date' => Carbon::now()->addDays(2)
            ],

            // المشروبات
            [
                'name' => 'عصير برتقال طبيعي - لتر',
                'description' => 'عصير برتقال طبيعي 100% بدون إضافات صناعية، منعش ومفيد وغني بفيتامين سي.',
                'price' => 2.200,
                'original_price' => 2.500,
                'stock' => 85,
                'sku' => 'JUICE-ORANGE-001',
                'image' => 'https://images.pexels.com/photos/1337825/pexels-photo-1337825.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/1337825/pexels-photo-1337825.jpeg'
                ],
                'origin' => 'البرازيل',
                'weight' => 1.000,
                'unit' => 'لتر',
                'is_fresh' => true,
                'is_featured' => false,
                'rating' => 4.3,
                'reviews_count' => 156,
                'sales_count' => 267,
                'nutritional_info' => [
                    'calories' => '45 سعرة حرارية لكل 100 مل',
                    'vitamin_c' => '50 مجم',
                    'sugar' => '10 جرام'
                ],
                'expiry_date' => Carbon::now()->addDays(5)
            ],

            // الحبوب والبقوليات
            [
                'name' => 'أرز بسمتي فاخر - 5 كيلو',
                'description' => 'أرز بسمتي فاخر طويل الحبة، عطر ولذيذ، مثالي لجميع الأطباق الشرقية والآسيوية.',
                'price' => 8.750,
                'original_price' => 9.500,
                'stock' => 40,
                'sku' => 'RICE-BASMATI-001',
                'image' => 'https://images.pexels.com/photos/723198/pexels-photo-723198.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/723198/pexels-photo-723198.jpeg'
                ],
                'origin' => 'الهند',
                'weight' => 5.000,
                'unit' => 'كيس',
                'is_fresh' => false,
                'is_featured' => true,
                'rating' => 4.8,
                'reviews_count' => 345,
                'sales_count' => 456,
                'nutritional_info' => [
                    'calories' => '130 سعرة حرارية لكل 100 جرام مطبوخ',
                    'carbs' => '28 جرام',
                    'protein' => '2.7 جرام'
                ],
                'expiry_date' => Carbon::now()->addMonths(12)
            ],
            [
                'name' => 'عدس أحمر - كيلو',
                'description' => 'عدس أحمر عالي الجودة، غني بالبروتين والألياف، مثالي للشوربات والأطباق النباتية.',
                'price' => 1.850,
                'original_price' => null,
                'stock' => 75,
                'sku' => 'LENTIL-RED-001',
                'image' => 'https://images.pexels.com/photos/4198018/pexels-photo-4198018.jpeg',
                'images' => [
                    'https://images.pexels.com/photos/4198018/pexels-photo-4198018.jpeg'
                ],
                'origin' => 'تركيا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => false,
                'is_featured' => false,
                'rating' => 4.5,
                'reviews_count' => 123,
                'sales_count' => 189,
                'nutritional_info' => [
                    'calories' => '116 سعرة حرارية لكل 100 جرام مطبوخ',
                    'protein' => '9 جرام',
                    'fiber' => '7.9 جرام'
                ],
                'expiry_date' => Carbon::now()->addMonths(18)
            ],

            // الزيوت والتوابل
            [
                'name' => 'زيت زيتون بكر ممتاز - 500 مل',
                'description' => 'زيت زيتون بكر ممتاز عالي الجودة، عصر على البارد، مثالي للسلطات والطبخ الصحي.',
                'price' => 4.500,
                'original_price' => 5.000,
                'stock' => 55,
                'sku' => 'OLIVE-OIL-001',
                'image' => 'https://images.pexels.com/photos/33783/olive-oil-salad-dressing-cooking-olive.jpg',
                'images' => [
                    'https://images.pexels.com/photos/33783/olive-oil-salad-dressing-cooking-olive.jpg'
                ],
                'origin' => 'إسبانيا',
                'weight' => 0.500,
                'unit' => 'زجاجة',
                'is_fresh' => false,
                'is_featured' => true,
                'rating' => 4.9,
                'reviews_count' => 278,
                'sales_count' => 345,
                'nutritional_info' => [
                    'calories' => '884 سعرة حرارية لكل 100 مل',
                    'vitamin_e' => '14.35 مجم',
                    'monounsaturated_fat' => '73 جرام'
                ],
                'expiry_date' => Carbon::now()->addMonths(24)
            ]
        ];

        // Get existing data for relationships
        $categories = Category::all();
        $vendors = Vendor::all();
        $governorates = Governorate::all();
        $cities = City::all();
        $brands = Brand::all();

        // Create default records if they don't exist
        if ($categories->isEmpty()) {
            $defaultCategory = Category::create([
                'name_ar' => 'منتجات عامة',
                'name_en' => 'General Products',
                'slug' => 'general-products',
                'is_active' => true,
                'sort_order' => 1
            ]);
            $categories = collect([$defaultCategory]);
        }

        if ($vendors->isEmpty()) {
            $defaultVendor = Vendor::create([
                'name' => 'متجر إنجب',
                'email' => 'vendor@engeb.com',
                'password' => bcrypt('password'),
                'phone' => '+96512345678',
                'address' => 'الكويت',
                'city' => 'الكويت',
                'governorate' => 'الكويت',
                'status' => 'approved',
                'is_active' => true
            ]);
            $vendors = collect([$defaultVendor]);
        }

        if ($governorates->isEmpty()) {
            $defaultGovernorate = Governorate::create([
                'name' => 'الكويت',
                'code' => 'KW'
            ]);
            $governorates = collect([$defaultGovernorate]);
        }

        if ($cities->isEmpty()) {
            $defaultCity = City::create([
                'name' => 'مدينة الكويت',
                'code' => 'KW-CITY',
                'governorate_id' => $governorates->first()->id
            ]);
            $cities = collect([$defaultCity]);
        }

        // Create products
        foreach ($products as $productData) {
            // Assign relationships
            $productData['category_id'] = $categories->random()->id;
            $productData['vendor_id'] = $vendors->random()->id;
            $productData['governorate_id'] = $governorates->random()->id;
            $productData['city_id'] = $cities->random()->id;
            $productData['brand_id'] = $brands->isNotEmpty() ? $brands->random()->id : null;
            
            // Set default values
            $productData['is_active'] = true;
            
            try {
                Product::create($productData);
            } catch (\Exception $e) {
                $this->command->error('خطأ في إنشاء المنتج: ' . $productData['name'] . ' - ' . $e->getMessage());
                continue;
            }
        }

        $this->command->info('تم إنشاء ' . count($products) . ' منتج بنجاح!');
    }
}
