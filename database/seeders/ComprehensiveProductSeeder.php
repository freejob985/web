<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use Carbon\Carbon;

class ComprehensiveProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create vendors
        $vendors = $this->getOrCreateVendors();
        
        // Get categories and subcategories
        $categories = Category::all();
        $subcategories = Subcategory::all();
        
        // Get brands
        $brands = Brand::all();

        // Clear existing products (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('products')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Comprehensive product data with 50 products
        $products = [
            // خضروات وفواكه طازجة (15 منتج)
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
                'name' => 'برتقال طازج - كيلو',
                'description' => 'برتقال طازج وعصيري، غني بفيتامين سي، مثالي للعصائر والوجبات الصحية.',
                'price' => 1.100,
                'original_price' => 1.300,
                'stock' => 90,
                'sku' => 'ORANGE-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 123,
                'sales_count' => 198,
                'nutritional_info' => json_encode([
                    'calories' => '47 سعرة حرارية لكل 100 جرام',
                    'vitamin_c' => '53.2 مجم',
                    'fiber' => '2.4 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(8),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'خس طازج - حبة',
                'description' => 'خس طازج ومقرمش، مثالي للسلطات والسندويشات، غني بالفيتامينات والمعادن.',
                'price' => 0.600,
                'original_price' => null,
                'stock' => 150,
                'sku' => 'LETTUCE-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.300,
                'unit' => 'حبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'reviews_count' => 67,
                'sales_count' => 145,
                'nutritional_info' => json_encode([
                    'calories' => '15 سعرة حرارية لكل 100 جرام',
                    'vitamin_k' => '126 مكجم',
                    'folate' => '38 مكجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'فلفل أحمر حلو - كيلو',
                'description' => 'فلفل أحمر حلو وطازج، غني بفيتامين سي، مثالي للطبخ والسلطات.',
                'price' => 1.400,
                'original_price' => 1.600,
                'stock' => 70,
                'sku' => 'PEPPER-RED-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'تركيا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 89,
                'sales_count' => 167,
                'nutritional_info' => json_encode([
                    'calories' => '31 سعرة حرارية لكل 100 جرام',
                    'vitamin_c' => '127.7 مجم',
                    'vitamin_a' => '3131 وحدة دولية'
                ]),
                'expiry_date' => Carbon::now()->addDays(6),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'بطاطس طازجة - كيلو',
                'description' => 'بطاطس طازجة ومقرمشة، مثالية للطبخ والقلي، غنية بالكربوهيدرات والطاقة.',
                'price' => 0.900,
                'original_price' => null,
                'stock' => 250,
                'sku' => 'POTATO-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 156,
                'sales_count' => 345,
                'nutritional_info' => json_encode([
                    'calories' => '77 سعرة حرارية لكل 100 جرام',
                    'carbs' => '17.5 جرام',
                    'potassium' => '429 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(14),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'عنب أحمر طازج - كيلو',
                'description' => 'عنب أحمر طازج وحلو، غني بمضادات الأكسدة، مثالي للوجبات الخفيفة.',
                'price' => 2.500,
                'original_price' => 3.000,
                'stock' => 60,
                'sku' => 'GRAPES-RED-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'تركيا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 78,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '62 سعرة حرارية لكل 100 جرام',
                    'resveratrol' => 'مضاد أكسدة قوي',
                    'vitamin_k' => '14.6 مكجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'فراولة طازجة - 250 جرام',
                'description' => 'فراولة طازجة وحلوة، غنية بفيتامين سي ومضادات الأكسدة، مثالية للحلويات والعصائر.',
                'price' => 2.200,
                'original_price' => 2.500,
                'stock' => 40,
                'sku' => 'STRAWBERRY-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'لبنان',
                'weight' => 0.250,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 45,
                'sales_count' => 89,
                'nutritional_info' => json_encode([
                    'calories' => '32 سعرة حرارية لكل 100 جرام',
                    'vitamin_c' => '58.8 مجم',
                    'manganese' => '0.4 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ليمون طازج - كيلو',
                'description' => 'ليمون طازج وعصيري، مثالي للعصائر والطبخ، غني بفيتامين سي.',
                'price' => 1.000,
                'original_price' => null,
                'stock' => 100,
                'sku' => 'LEMON-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 98,
                'sales_count' => 178,
                'nutritional_info' => json_encode([
                    'calories' => '29 سعرة حرارية لكل 100 جرام',
                    'vitamin_c' => '53 مجم',
                    'citric_acid' => '5.6 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'بصل أحمر طازج - كيلو',
                'description' => 'بصل أحمر طازج وحار، مثالي للطبخ والسلطات، غني بمضادات الأكسدة.',
                'price' => 0.700,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'ONION-RED-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'reviews_count' => 112,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '40 سعرة حرارية لكل 100 جرام',
                    'quercetin' => 'مضاد أكسدة قوي',
                    'vitamin_c' => '7.4 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(21),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ثوم طازج - 100 جرام',
                'description' => 'ثوم طازج وعطري، مثالي للطبخ والتوابل، غني بمضادات الأكسدة والمركبات المفيدة.',
                'price' => 1.500,
                'original_price' => null,
                'stock' => 80,
                'sku' => 'GARLIC-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'مصر',
                'weight' => 0.100,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 67,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '149 سعرة حرارية لكل 100 جرام',
                    'allicin' => 'مركب مضاد للبكتيريا',
                    'manganese' => '1.7 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كوسا طازجة - كيلو',
                'description' => 'كوسا طازجة ومقرمشة، مثالية للطبخ والسلطات، غنية بالفيتامينات والمعادن.',
                'price' => 1.100,
                'original_price' => 1.300,
                'stock' => 90,
                'sku' => 'ZUCCHINI-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 56,
                'sales_count' => 98,
                'nutritional_info' => json_encode([
                    'calories' => '17 سعرة حرارية لكل 100 جرام',
                    'vitamin_c' => '17.9 مجم',
                    'potassium' => '261 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ],

            // منتجات الألبان (10 منتجات)
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
                'name' => 'زبدة طازجة - 200 جرام',
                'description' => 'زبدة طازجة ومصنوعة من الحليب الطبيعي، مثالية للطبخ والخبز.',
                'price' => 2.500,
                'original_price' => null,
                'stock' => 45,
                'sku' => 'BUTTER-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'نيوزيلندا',
                'weight' => 0.200,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 78,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '717 سعرة حرارية لكل 100 جرام',
                    'fat' => '81 جرام',
                    'vitamin_a' => '684 مكجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(14),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'لبن زبادي طبيعي - 500 جرام',
                'description' => 'لبن زبادي طبيعي ومخمر، غني بالبروبيوتيك، مفيد للهضم والصحة العامة.',
                'price' => 1.200,
                'original_price' => 1.400,
                'stock' => 120,
                'sku' => 'YOGURT-NATURAL-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 134,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '59 سعرة حرارية لكل 100 جرام',
                    'protein' => '10 جرام',
                    'probiotics' => 'مفيد للهضم',
                    'calcium' => '110 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'جبن شيدر - 200 جرام',
                'description' => 'جبن شيدر طازج وناضج، مثالي للسندويشات والطبخ، غني بالبروتين والكالسيوم.',
                'price' => 3.200,
                'original_price' => 3.500,
                'stock' => 35,
                'sku' => 'CHEESE-CHEDDAR-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'بريطانيا',
                'weight' => 0.200,
                'unit' => 'علبة',
                'is_fresh' => false,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 67,
                'sales_count' => 98,
                'nutritional_info' => json_encode([
                    'calories' => '403 سعرة حرارية لكل 100 جرام',
                    'protein' => '25 جرام',
                    'calcium' => '721 مجم',
                    'fat' => '33 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(21),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كريمة طازجة - 250 مل',
                'description' => 'كريمة طازجة وخفيفة، مثالية للطبخ والحلويات، مصنوعة من الحليب الطبيعي.',
                'price' => 1.800,
                'original_price' => null,
                'stock' => 50,
                'sku' => 'CREAM-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.250,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 45,
                'sales_count' => 78,
                'nutritional_info' => json_encode([
                    'calories' => '345 سعرة حرارية لكل 100 مل',
                    'fat' => '37 جرام',
                    'calcium' => '65 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(4),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'حليب خالي الدسم - لتر',
                'description' => 'حليب خالي الدسم وغني بالبروتين، مثالي للحمية والصحة العامة.',
                'price' => 0.600,
                'original_price' => null,
                'stock' => 150,
                'sku' => 'MILK-SKIM-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.000,
                'unit' => 'لتر',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 89,
                'sales_count' => 167,
                'nutritional_info' => json_encode([
                    'calories' => '34 سعرة حرارية لكل 100 مل',
                    'protein' => '3.4 جرام',
                    'calcium' => '122 مجم',
                    'fat' => '0.2 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'جبن موزاريلا - 200 جرام',
                'description' => 'جبن موزاريلا طازج وطري، مثالي للبيتزا والسلطات، غني بالبروتين.',
                'price' => 2.800,
                'original_price' => 3.200,
                'stock' => 40,
                'sku' => 'CHEESE-MOZZARELLA-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'إيطاليا',
                'weight' => 0.200,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 78,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '280 سعرة حرارية لكل 100 جرام',
                    'protein' => '22 جرام',
                    'calcium' => '505 مجم',
                    'fat' => '22 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'لبن زبادي بالفواكه - 150 جرام',
                'description' => 'لبن زبادي بالفواكه الطبيعية، حلو ولذيذ، غني بالبروبيوتيك والفيتامينات.',
                'price' => 0.800,
                'original_price' => 1.000,
                'stock' => 100,
                'sku' => 'YOGURT-FRUIT-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.150,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 112,
                'sales_count' => 189,
                'nutritional_info' => json_encode([
                    'calories' => '85 سعرة حرارية لكل 100 جرام',
                    'protein' => '3.5 جرام',
                    'sugar' => '12 جرام',
                    'probiotics' => 'مفيد للهضم'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],

            // منتجات اللحوم والدجاج (10 منتجات)
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
                'name' => 'لحم بقري طازج - كيلو',
                'description' => 'لحم بقري طازج وعالي الجودة، مثالي للطبخ والشواء، غني بالبروتين والحديد.',
                'price' => 8.500,
                'original_price' => 9.000,
                'stock' => 25,
                'sku' => 'BEEF-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'أستراليا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 89,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '250 سعرة حرارية لكل 100 جرام',
                    'protein' => '26 جرام',
                    'iron' => '2.6 مجم',
                    'fat' => '15 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'سمك طازج - كيلو',
                'description' => 'سمك طازج من البحر، غني بأوميغا 3 والبروتين، مثالي للطبخ الصحي.',
                'price' => 6.000,
                'original_price' => 7.000,
                'stock' => 30,
                'sku' => 'FISH-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الخليج العربي',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 78,
                'sales_count' => 112,
                'nutritional_info' => json_encode([
                    'calories' => '206 سعرة حرارية لكل 100 جرام',
                    'protein' => '22 جرام',
                    'omega_3' => '1.2 جرام',
                    'vitamin_d' => '988 وحدة دولية'
                ]),
                'expiry_date' => Carbon::now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'جمبري طازج - 500 جرام',
                'description' => 'جمبري طازج ولذيذ، غني بالبروتين وأوميغا 3، مثالي للطبخ والمقبلات.',
                'price' => 12.000,
                'original_price' => 15.000,
                'stock' => 20,
                'sku' => 'SHRIMP-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الخليج العربي',
                'weight' => 0.500,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 45,
                'sales_count' => 67,
                'nutritional_info' => json_encode([
                    'calories' => '99 سعرة حرارية لكل 100 جرام',
                    'protein' => '24 جرام',
                    'omega_3' => '0.3 جرام',
                    'selenium' => '48 مكجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'لحم خروف طازج - كيلو',
                'description' => 'لحم خروف طازج وعالي الجودة، مثالي للطبخ التقليدي والشواء، غني بالبروتين.',
                'price' => 7.500,
                'original_price' => 8.500,
                'stock' => 35,
                'sku' => 'LAMB-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'نيوزيلندا',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 67,
                'sales_count' => 98,
                'nutritional_info' => json_encode([
                    'calories' => '294 سعرة حرارية لكل 100 جرام',
                    'protein' => '25 جرام',
                    'iron' => '2.3 مجم',
                    'fat' => '21 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'دجاج مقطع - 500 جرام',
                'description' => 'دجاج مقطع ومغلف، جاهز للطبخ، طري ولذيذ، مثالي للوجبات السريعة.',
                'price' => 2.200,
                'original_price' => null,
                'stock' => 60,
                'sku' => 'CHICKEN-CUT-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 89,
                'sales_count' => 145,
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
                'name' => 'نقانق طازجة - 400 جرام',
                'description' => 'نقانق طازجة ومصنوعة من اللحم الطبيعي، مثالية للشواء والطبخ.',
                'price' => 4.500,
                'original_price' => 5.000,
                'stock' => 40,
                'sku' => 'SAUSAGE-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'ألمانيا',
                'weight' => 0.400,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'reviews_count' => 56,
                'sales_count' => 78,
                'nutritional_info' => json_encode([
                    'calories' => '301 سعرة حرارية لكل 100 جرام',
                    'protein' => '13 جرام',
                    'fat' => '27 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'لحم مفروم طازج - 500 جرام',
                'description' => 'لحم مفروم طازج وعالي الجودة، مثالي للطبخ والبرجر، غني بالبروتين.',
                'price' => 4.200,
                'original_price' => null,
                'stock' => 50,
                'sku' => 'GROUND-BEEF-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'أستراليا',
                'weight' => 0.500,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 78,
                'sales_count' => 112,
                'nutritional_info' => json_encode([
                    'calories' => '254 سعرة حرارية لكل 100 جرام',
                    'protein' => '26 جرام',
                    'iron' => '2.6 مجم',
                    'fat' => '15 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'دجاج مشوي جاهز - حبة',
                'description' => 'دجاج مشوي جاهز للأكل، طري ولذيذ، مثالي للوجبات السريعة.',
                'price' => 8.000,
                'original_price' => 10.000,
                'stock' => 15,
                'sku' => 'CHICKEN-ROASTED-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 1.200,
                'unit' => 'حبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 45,
                'sales_count' => 67,
                'nutritional_info' => json_encode([
                    'calories' => '165 سعرة حرارية لكل 100 جرام',
                    'protein' => '31 جرام',
                    'fat' => '3.6 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كباب طازج - 500 جرام',
                'description' => 'كباب طازج ومتبل، جاهز للشواء، مصنوع من اللحم الطازج والتوابل الطبيعية.',
                'price' => 6.500,
                'original_price' => 7.500,
                'stock' => 25,
                'sku' => 'KEBAB-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'كيلو',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 67,
                'sales_count' => 89,
                'nutritional_info' => json_encode([
                    'calories' => '250 سعرة حرارية لكل 100 جرام',
                    'protein' => '26 جرام',
                    'iron' => '2.6 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],

            // منتجات المخبوزات (10 منتجات)
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
            ],
            [
                'name' => 'خبز توست أبيض - 500 جرام',
                'description' => 'خبز توست أبيض طازج ومقرمش، مثالي للإفطار والسندويشات.',
                'price' => 1.200,
                'original_price' => null,
                'stock' => 150,
                'sku' => 'BREAD-TOAST-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 123,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '265 سعرة حرارية لكل 100 جرام',
                    'carbs' => '49 جرام',
                    'protein' => '9 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كرواسان طازج - 6 قطع',
                'description' => 'كرواسان طازج ومخبوز يومياً، طري ومقرمش، مثالي للإفطار والوجبات الخفيفة.',
                'price' => 2.500,
                'original_price' => 3.000,
                'stock' => 80,
                'sku' => 'CROISSANT-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.300,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 67,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '406 سعرة حرارية لكل 100 جرام',
                    'carbs' => '45 جرام',
                    'fat' => '21 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كعك بالتمر - 500 جرام',
                'description' => 'كعك بالتمر طازج وحلو، مصنوع من التمر الطبيعي والدقيق الأبيض.',
                'price' => 3.500,
                'original_price' => 4.000,
                'stock' => 60,
                'sku' => 'CAKE-DATES-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 45,
                'sales_count' => 78,
                'nutritional_info' => json_encode([
                    'calories' => '350 سعرة حرارية لكل 100 جرام',
                    'carbs' => '65 جرام',
                    'sugar' => '25 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'بسكويت شاي - 400 جرام',
                'description' => 'بسكويت شاي طازج ومقرمش، مثالي مع الشاي والقهوة، مصنوع من الدقيق الطبيعي.',
                'price' => 1.800,
                'original_price' => null,
                'stock' => 120,
                'sku' => 'BISCUIT-TEA-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.400,
                'unit' => 'علبة',
                'is_fresh' => false,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'reviews_count' => 89,
                'sales_count' => 156,
                'nutritional_info' => json_encode([
                    'calories' => '450 سعرة حرارية لكل 100 جرام',
                    'carbs' => '65 جرام',
                    'fat' => '18 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'فطيرة بالجبن - 4 قطع',
                'description' => 'فطيرة بالجبن طازجة ومخبوزة يومياً، طري ومقرمش، مثالية للإفطار والوجبات الخفيفة.',
                'price' => 2.200,
                'original_price' => 2.500,
                'stock' => 70,
                'sku' => 'PIE-CHEESE-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.400,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 56,
                'sales_count' => 98,
                'nutritional_info' => json_encode([
                    'calories' => '320 سعرة حرارية لكل 100 جرام',
                    'carbs' => '35 جرام',
                    'protein' => '12 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'خبز بر - 500 جرام',
                'description' => 'خبز بر طازج وصحي، مصنوع من الحبوب الكاملة، غني بالألياف والبروتين.',
                'price' => 1.800,
                'original_price' => null,
                'stock' => 100,
                'sku' => 'BREAD-BROWN-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 78,
                'sales_count' => 134,
                'nutritional_info' => json_encode([
                    'calories' => '247 سعرة حرارية لكل 100 جرام',
                    'carbs' => '41 جرام',
                    'fiber' => '7 جرام',
                    'protein' => '13 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'دونات طازجة - 6 قطع',
                'description' => 'دونات طازجة ومقرمشة، مغطاة بالسكر، مثالية للحلويات والوجبات الخفيفة.',
                'price' => 3.000,
                'original_price' => 3.500,
                'stock' => 50,
                'sku' => 'DONUT-FRESH-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.300,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 45,
                'sales_count' => 78,
                'nutritional_info' => json_encode([
                    'calories' => '452 سعرة حرارية لكل 100 جرام',
                    'carbs' => '49 جرام',
                    'sugar' => '19 جرام',
                    'fat' => '25 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'كيك بالشوكولاتة - 500 جرام',
                'description' => 'كيك بالشوكولاتة طازج ولذيذ، مصنوع من الشوكولاتة الطبيعية والدقيق الأبيض.',
                'price' => 4.500,
                'original_price' => 5.000,
                'stock' => 30,
                'sku' => 'CAKE-CHOCOLATE-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.500,
                'unit' => 'علبة',
                'is_fresh' => true,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 34,
                'sales_count' => 56,
                'nutritional_info' => json_encode([
                    'calories' => '371 سعرة حرارية لكل 100 جرام',
                    'carbs' => '53 جرام',
                    'sugar' => '31 جرام',
                    'fat' => '15 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'خبز بالزعتر - 4 قطع',
                'description' => 'خبز بالزعتر طازج ومخبوز يومياً، طري ومقرمش، مثالي للإفطار والوجبات الخفيفة.',
                'price' => 1.500,
                'original_price' => null,
                'stock' => 90,
                'sku' => 'BREAD-THYME-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الكويت',
                'weight' => 0.300,
                'unit' => 'كيس',
                'is_fresh' => true,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'reviews_count' => 67,
                'sales_count' => 112,
                'nutritional_info' => json_encode([
                    'calories' => '275 سعرة حرارية لكل 100 جرام',
                    'carbs' => '56 جرام',
                    'protein' => '9 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now()
            ],

            // منتجات أخرى (5 منتجات)
            [
                'name' => 'أرز بسمتي طويل الحبة - كيلو',
                'description' => 'أرز بسمتي طويل الحبة وعالي الجودة، مثالي للطبخ العربي والهندي، طري ولذيذ.',
                'price' => 2.200,
                'original_price' => null,
                'stock' => 200,
                'sku' => 'RICE-BASMATI-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'الهند',
                'weight' => 1.000,
                'unit' => 'كيلو',
                'is_fresh' => false,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.7,
                'reviews_count' => 156,
                'sales_count' => 345,
                'nutritional_info' => json_encode([
                    'calories' => '130 سعرة حرارية لكل 100 جرام',
                    'carbs' => '28 جرام',
                    'protein' => '2.7 جرام'
                ]),
                'expiry_date' => Carbon::now()->addDays(365),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'زيت زيتون بكر ممتاز - 500 مل',
                'description' => 'زيت زيتون بكر ممتاز وعالي الجودة، مثالي للطبخ والسلطات، غني بمضادات الأكسدة.',
                'price' => 8.500,
                'original_price' => 10.000,
                'stock' => 80,
                'sku' => 'OLIVE-OIL-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'إسبانيا',
                'weight' => 0.500,
                'unit' => 'زجاجة',
                'is_fresh' => false,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 89,
                'sales_count' => 167,
                'nutritional_info' => json_encode([
                    'calories' => '884 سعرة حرارية لكل 100 مل',
                    'fat' => '100 جرام',
                    'monounsaturated' => '73 جرام',
                    'vitamin_e' => '14 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(730),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'عسل طبيعي - 500 جرام',
                'description' => 'عسل طبيعي وعالي الجودة، غني بمضادات الأكسدة والفيتامينات، مثالي للتحلية الطبيعية.',
                'price' => 12.000,
                'original_price' => 15.000,
                'stock' => 60,
                'sku' => 'HONEY-NATURAL-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'لبنان',
                'weight' => 0.500,
                'unit' => 'زجاجة',
                'is_fresh' => false,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'reviews_count' => 78,
                'sales_count' => 123,
                'nutritional_info' => json_encode([
                    'calories' => '304 سعرة حرارية لكل 100 جرام',
                    'sugar' => '82 جرام',
                    'antioxidants' => 'مضادات أكسدة قوية',
                    'vitamin_c' => '0.5 مجم'
                ]),
                'expiry_date' => Carbon::now()->addDays(1095),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'قهوة عربية مطحونة - 250 جرام',
                'description' => 'قهوة عربية مطحونة وعالية الجودة، مثالية للقهوة العربية التقليدية، عطرة ولذيذة.',
                'price' => 6.500,
                'original_price' => 8.000,
                'stock' => 100,
                'sku' => 'COFFEE-ARABIC-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'اليمن',
                'weight' => 0.250,
                'unit' => 'كيس',
                'is_fresh' => false,
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'reviews_count' => 112,
                'sales_count' => 189,
                'nutritional_info' => json_encode([
                    'calories' => '2 سعرة حرارية لكل 100 مل',
                    'caffeine' => '95 مجم',
                    'antioxidants' => 'مضادات أكسدة قوية'
                ]),
                'expiry_date' => Carbon::now()->addDays(180),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'شاي أحمر - 100 جرام',
                'description' => 'شاي أحمر عالي الجودة، مثالي للشاي التقليدي، عطري ومنعش.',
                'price' => 3.200,
                'original_price' => null,
                'stock' => 150,
                'sku' => 'TEA-RED-001',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'images' => json_encode([
                    'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg'
                ]),
                'origin' => 'سريلانكا',
                'weight' => 0.100,
                'unit' => 'كيس',
                'is_fresh' => false,
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'reviews_count' => 134,
                'sales_count' => 234,
                'nutritional_info' => json_encode([
                    'calories' => '1 سعرة حرارية لكل 100 مل',
                    'caffeine' => '40 مجم',
                    'antioxidants' => 'مضادات أكسدة قوية'
                ]),
                'expiry_date' => Carbon::now()->addDays(730),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert products using DB::table for better compatibility
        foreach ($products as $product) {
            try {
                // Assign random vendor, category, subcategory, and brand
                $product['vendor_id'] = $vendors->random()->id;
                $product['category_id'] = $categories->random()->id;
                $product['subcategory_id'] = $subcategories->random()->id;
                $product['brand_id'] = $brands->random()->id;
                
                \DB::table('products')->insert($product);
                $this->command->info('تم إنشاء المنتج: ' . $product['name']);
            } catch (\Exception $e) {
                $this->command->error('خطأ في إنشاء المنتج: ' . $product['name'] . ' - ' . $e->getMessage());
            }
        }

        $this->command->info('تم إنشاء ' . count($products) . ' منتج بنجاح!');
    }

    private function getOrCreateVendors()
    {
        $vendors = Vendor::all();
        
        if ($vendors->isEmpty()) {
            $vendors = collect([
                Vendor::create([
                    'name' => 'متجر إنجب',
                    'email' => 'vendor@engeb.com',
                    'password' => bcrypt('password'),
                    'phone' => '+96512345678',
                    'address' => 'الكويت',
                    'city' => 'الكويت',
                    'governorate' => 'الكويت',
                    'status' => 'approved',
                    'is_active' => true
                ]),
                Vendor::create([
                    'name' => 'مزارع الطيبات',
                    'email' => 'vendor2@engeb.com',
                    'password' => bcrypt('password'),
                    'phone' => '+96512345679',
                    'address' => 'الكويت',
                    'city' => 'الكويت',
                    'governorate' => 'الكويت',
                    'status' => 'approved',
                    'is_active' => true
                ]),
                Vendor::create([
                    'name' => 'البستان الطازج',
                    'email' => 'vendor3@engeb.com',
                    'password' => bcrypt('password'),
                    'phone' => '+96512345680',
                    'address' => 'الكويت',
                    'city' => 'الكويت',
                    'governorate' => 'الكويت',
                    'status' => 'approved',
                    'is_active' => true
                ])
            ]);
        }
        
        return $vendors;
    }
}
