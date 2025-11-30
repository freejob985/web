<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;

class SimpleProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a default vendor
        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
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
        }

        // Clear existing products (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('products')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Simple product data without complex relationships
        $products = [
            [
                'name' => 'تفاح أحمر طازج - كيلو',
                'description' => 'تفاح أحمر طازج ولذيذ، مستورد من أجود المزارع. غني بالفيتامينات والألياف الطبيعية.',
                'price' => 1.250,
                'original_price' => 1.500,
                'stock' => 150,
                'sku' => 'APPLE-RED-001',
                'image' => 'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                    'https://images.pexels.com/photos/1510392/pexels-photo-1510392.jpeg'
                ]),
                'origin' => 'تركيا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 156,
                'sales_count' => 342,
                'nutritional_info' => json_encode([
                    'calories' => '52 سعرة حرارية لكل 100 جرام',
                    'carbs' => '14 جرام',
                    'fiber' => '2.4 جرام',
                    'vitamin_c' => '4.6 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'خيار طازج محلي - كيلو',
                'description' => 'خيار طازج من المزارع المحلية الكويتية، مقرمش ومنعش، مثالي للسلطات والعصائر.',
                'price' => 0.800,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'CUCUMBER-LOCAL-001',
                'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 89,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '16 سعرة حرارية لكل 100 جرام',
                    'water' => '95%',
                    'vitamin_k' => '16.4 مكجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'طماطم كرزية طازجة - 500 جرام',
                'description' => 'طماطم كرزية صغيرة وحلوة المذاق، مثالية للسلطات والوجبات الخفيفة.',
                'price' => 1.200,
                'original_price' => 1.400,
                'stock' => 80,
                'sku' => 'TOMATO-CHERRY-001',
                'image' => 'https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg'
                ]),
                'origin' => 'لبنان',
                'weight' => 0.500,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 67,
                'sales_count' => 156,
                'nutritional_info' => json_encode([
                    'calories' => '18 سعرة حرارية لكل 100 جرام',
                    'lycopene' => 'مضاد أكسدة قوي',
                    'vitamin_c' => '13.7 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(4),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'موز طازج - كيلو',
                'description' => 'موز طازج وناضج، غني بالبوتاسيوم والطاقة الطبيعية، مثالي للإفطار والوجبات الخفيفة.',
                'price' => 0.950,
                'original_price' => null,
                'stock' => 120,
                'sku' => 'BANANA-FRESH-001',
                'image' => 'https://images.pexels.com/photos/2872755/pexels-photo-2872755.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/2872755/pexels-photo-2872755.jpeg'
                ]),
                'origin' => 'الإكوادور',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 134,
                'sales_count' => 289,
                'nutritional_info' => json_encode([
                    'calories' => '89 سعرة حرارية لكل 100 جرام',
                    'potassium' => '358 مجم',
                    'vitamin_b6' => '0.4 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(6),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'جزر طازج - كيلو',
                'description' => 'جزر طازج ومقرمش، غني بفيتامين أ والبيتا كاروتين، مفيد للنظر والصحة العامة.',
                'price' => 0.750,
                'original_price' => null,
                'stock' => 180,
                'sku' => 'CARROT-FRESH-001',
                'image' => 'https://images.pexels.com/photos/3650647/pexels-photo-3650647.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/3650647/pexels-photo-3650647.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 78,
                'sales_count' => 167,
                'nutritional_info' => json_encode([
                    'calories' => '41 سعرة حرارية لكل 100 جرام',
                    'beta_carotene' => '8285 مكجم',
                    'fiber' => '2.8 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'حليب طازج كامل الدسم - لتر',
                'description' => 'حليب طازج كامل الدسم من أجود المزارع المحلية، غني بالكالسيوم والبروتين.',
                'price' => 0.650,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'MILK-FULL-001',
                'image' => 'https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'لتر',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 245,
                'sales_count' => 567,
                'nutritional_info' => json_encode([
                    'calories' => '61 سعرة حرارية لكل 100 مل',
                    'protein' => '3.2 جرام',
                    'calcium' => '113 مجم',
                    'fat' => '3.2 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'جبن أبيض طازج - 250 جرام',
                'description' => 'جبن أبيض طازج وطري، مصنوع من حليب طبيعي 100%، مثالي للإفطار والسندويشات.',
                'price' => 1.800,
                'original_price' => 2.000,
                'stock' => 60,
                'sku' => 'CHEESE-WHITE-001',
                'image' => 'https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg'
                ]),
                'origin' => 'الدنمارك',
                'weight' => 0.250,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'reviews_count' => 92,
                'sales_count' => 134,
                'nutritional_info' => json_encode([
                    'calories' => '264 سعرة حرارية لكل 100 جرام',
                    'protein' => '25 جرام',
                    'calcium' => '710 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'دجاج طازج محلي - كيلو',
                'description' => 'دجاج طازج من المزارع المحلية، مربى بطريقة طبيعية وصحية، لحم طري ولذيذ.',
                'price' => 3.500,
                'original_price' => null,
                'stock' => 45,
                'sku' => 'CHICKEN-LOCAL-001',
                'image' => 'https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 167,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '165 سعرة حرارية لكل 100 جرام',
                    'protein' => '31 جرام',
                    'fat' => '3.6 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'خبز عربي طازج - 5 أرغفة',
                'description' => 'خبز عربي طازج ومخبوز يومياً، طري ولذيذ، مثالي لجميع الوجبات.',
                'price' => 0.500,
                'original_price' => null,
                'stock' => 300,
                'sku' => 'BREAD-ARABIC-001',
                'image' => 'https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.400,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 234,
                'sales_count' => 678,
                'nutritional_info' => json_encode([
                    'calories' => '275 سعرة حرارية لكل 100 جرام',
                    'carbs' => '56 جرام',
                    'protein' => '9 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert products using DB::table for better compatibility
        foreach ($products as $product) {
            try {
                $product['vendor_id'] = $vendor->id;
                \DB::table('products')->insert($product);
                $this->command->info('تم إنشاء المنتج: ' . $product['name']);
            } catch (\Exception $e) {
                $this->command->error('خطأ في إنشاء المنتج: ' . $product['name'] . ' - ' . $e->getMessage());
            }
        }

        $this->command->info('تم إنشاء ' . count($products) . ' منتج بنجاح!');
    }
}
