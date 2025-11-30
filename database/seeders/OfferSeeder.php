<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\OfferCategory;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = OfferCategory::all();
        
        if ($categories->isEmpty()) {
            $this->command->warn('No offer categories found. Please run OfferCategorySeeder first.');
            return;
        }

        $offers = [
            // خضروات وفواكه
            [
                'offer_category_id' => $categories->where('slug', 'vegetables-fruits')->first()->id,
                'vendor_id' => 1, // افتراضي
                'title' => 'خصم 50% على الخضروات الطازجة',
                'slug' => 'vegetables-50-offer',
                'description' => 'احصل على خصم 50% على جميع الخضروات الطازجة من المزرعة مباشرة',
                'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                'original_price' => 10.00,
                'offer_price' => 5.00,
                'discount_percentage' => 50.00,
                'discount_amount' => 5.00,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'stock' => 100,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'is_active' => true,
                'is_featured' => true,
                'is_limited_time' => false,
                'sort_order' => 1
            ],
            [
                'offer_category_id' => $categories->where('slug', 'vegetables-fruits')->first()->id,
                'vendor_id' => 1,
                'title' => 'عرض 2+1 على الفواكه الموسمية',
                'slug' => 'fruits-2-plus-1',
                'description' => 'اشتر أي فاكهتين واحصل على الثالثة مجاناً',
                'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'original_price' => 15.00,
                'offer_price' => 10.00,
                'discount_percentage' => 33.33,
                'discount_amount' => 5.00,
                'min_quantity' => 2,
                'max_quantity' => 20,
                'stock' => 50,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(5),
                'is_active' => true,
                'is_featured' => false,
                'is_limited_time' => true,
                'sort_order' => 2
            ],
            
            // منتجات الألبان
            [
                'offer_category_id' => $categories->where('slug', 'dairy-products')->first()->id,
                'vendor_id' => 1,
                'title' => 'عرض 2+1 على منتجات الألبان',
                'slug' => 'dairy-2-plus-1',
                'description' => 'اشتر أي منتجين من الألبان واحصل على الثالث مجاناً',
                'image' => 'https://images.pexels.com/photos/8064204/pexels-photo-8064204.jpeg',
                'original_price' => 15.00,
                'offer_price' => 10.00,
                'discount_percentage' => 33.33,
                'discount_amount' => 5.00,
                'min_quantity' => 2,
                'max_quantity' => 15,
                'stock' => 75,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'is_active' => true,
                'is_featured' => false,
                'is_limited_time' => false,
                'sort_order' => 3
            ],
            [
                'offer_category_id' => $categories->where('slug', 'dairy-products')->first()->id,
                'vendor_id' => 1,
                'title' => 'خصم 30% على الجبن الطازج',
                'slug' => 'fresh-cheese-30-offer',
                'description' => 'جبن طازج من أفضل المزارع بخصم 30%',
                'image' => 'https://images.pexels.com/photos/1267320/pexels-photo-1267320.jpeg',
                'original_price' => 20.00,
                'offer_price' => 14.00,
                'discount_percentage' => 30.00,
                'discount_amount' => 6.00,
                'min_quantity' => 1,
                'max_quantity' => 8,
                'stock' => 40,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(4),
                'is_active' => true,
                'is_featured' => true,
                'is_limited_time' => false,
                'sort_order' => 4
            ],
            
            // لحوم ودواجن
            [
                'offer_category_id' => $categories->where('slug', 'meat-poultry')->first()->id,
                'vendor_id' => 1,
                'title' => 'خصم 30% على اللحوم الطازجة',
                'slug' => 'fresh-meat-30-offer',
                'description' => 'لحوم طازجة من أفضل المزارع بخصم 30%',
                'image' => 'https://images.pexels.com/photos/19352815/pexels-photo-19352815.jpeg',
                'original_price' => 25.00,
                'offer_price' => 17.50,
                'discount_percentage' => 30.00,
                'discount_amount' => 7.50,
                'min_quantity' => 1,
                'max_quantity' => 5,
                'stock' => 30,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(2),
                'is_active' => true,
                'is_featured' => true,
                'is_limited_time' => false,
                'sort_order' => 5
            ],
            [
                'offer_category_id' => $categories->where('slug', 'meat-poultry')->first()->id,
                'vendor_id' => 1,
                'title' => 'عرض خاص على الدواجن',
                'slug' => 'poultry-special-offer',
                'description' => 'دواجن طازجة بخصم 25%',
                'image' => 'https://images.pexels.com/photos/106343/pexels-photo-106343.jpeg',
                'original_price' => 18.00,
                'offer_price' => 13.50,
                'discount_percentage' => 25.00,
                'discount_amount' => 4.50,
                'min_quantity' => 1,
                'max_quantity' => 6,
                'stock' => 25,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(6),
                'is_active' => true,
                'is_featured' => false,
                'is_limited_time' => true,
                'sort_order' => 6
            ],
            
            // مخبوزات
            [
                'offer_category_id' => $categories->where('slug', 'bakery')->first()->id,
                'vendor_id' => 1,
                'title' => 'عرض خاص على المخبوزات',
                'slug' => 'bakery-special-offer',
                'description' => 'مخبوزات طازجة من الفرن مباشرة بخصم 25%',
                'image' => 'https://images.pexels.com/photos/2680601/pexels-photo-2680601.jpeg',
                'original_price' => 8.00,
                'offer_price' => 6.00,
                'discount_percentage' => 25.00,
                'discount_amount' => 2.00,
                'min_quantity' => 1,
                'max_quantity' => 12,
                'stock' => 60,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(1),
                'is_active' => true,
                'is_featured' => false,
                'is_limited_time' => true,
                'sort_order' => 7
            ],
            [
                'offer_category_id' => $categories->where('slug', 'bakery')->first()->id,
                'vendor_id' => 1,
                'title' => 'خصم 40% على الخبز الطازج',
                'slug' => 'fresh-bread-40-offer',
                'description' => 'خبز طازج من الفرن بخصم 40%',
                'image' => 'https://images.pexels.com/photos/1775043/pexels-photo-1775043.jpeg',
                'original_price' => 5.00,
                'offer_price' => 3.00,
                'discount_percentage' => 40.00,
                'discount_amount' => 2.00,
                'min_quantity' => 1,
                'max_quantity' => 20,
                'stock' => 80,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(3),
                'is_active' => true,
                'is_featured' => true,
                'is_limited_time' => false,
                'sort_order' => 8
            ],
            
            // مشروبات
            [
                'offer_category_id' => $categories->where('slug', 'beverages')->first()->id,
                'vendor_id' => 1,
                'title' => 'خصم 35% على العصائر الطبيعية',
                'slug' => 'natural-juices-35-offer',
                'description' => 'عصائر طبيعية 100% بخصم 35%',
                'image' => 'https://images.pexels.com/photos/1435735/pexels-photo-1435735.jpeg',
                'original_price' => 12.00,
                'offer_price' => 7.80,
                'discount_percentage' => 35.00,
                'discount_amount' => 4.20,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'stock' => 45,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(4),
                'is_active' => true,
                'is_featured' => false,
                'is_limited_time' => false,
                'sort_order' => 9
            ],
            
            // حلويات
            [
                'offer_category_id' => $categories->where('slug', 'sweets')->first()->id,
                'vendor_id' => 1,
                'title' => 'خصم 45% على الحلويات الشرقية',
                'slug' => 'eastern-sweets-45-offer',
                'description' => 'حلويات شرقية تقليدية بخصم 45%',
                'image' => 'https://images.pexels.com/photos/1028741/pexels-photo-1028741.jpeg',
                'original_price' => 22.00,
                'offer_price' => 12.10,
                'discount_percentage' => 45.00,
                'discount_amount' => 9.90,
                'min_quantity' => 1,
                'max_quantity' => 8,
                'stock' => 35,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(5),
                'is_active' => true,
                'is_featured' => true,
                'is_limited_time' => false,
                'sort_order' => 10
            ]
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
