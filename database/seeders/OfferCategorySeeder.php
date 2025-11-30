<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OfferCategory;

class OfferCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'خضروات وفواكه',
                'slug' => 'vegetables-fruits',
                'description' => 'عروض خاصة على الخضروات والفواكه الطازجة',
                'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                'color' => 'bg-green-100 text-green-600',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'منتجات الألبان',
                'slug' => 'dairy-products',
                'description' => 'عروض على منتجات الألبان والجبن',
                'image' => 'https://images.pexels.com/photos/8064204/pexels-photo-8064204.jpeg',
                'color' => 'bg-blue-100 text-blue-600',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'لحوم ودواجن',
                'slug' => 'meat-poultry',
                'description' => 'عروض على اللحوم والدواجن الطازجة',
                'image' => 'https://images.pexels.com/photos/19352815/pexels-photo-19352815.jpeg',
                'color' => 'bg-red-100 text-red-600',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'مخبوزات',
                'slug' => 'bakery',
                'description' => 'عروض على المخبوزات الطازجة',
                'image' => 'https://images.pexels.com/photos/2680601/pexels-photo-2680601.jpeg',
                'color' => 'bg-orange-100 text-orange-600',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'مشروبات',
                'slug' => 'beverages',
                'description' => 'عروض على المشروبات والعصائر',
                'image' => 'https://images.pexels.com/photos/1435735/pexels-photo-1435735.jpeg',
                'color' => 'bg-purple-100 text-purple-600',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'حلويات',
                'slug' => 'sweets',
                'description' => 'عروض على الحلويات والمعجنات',
                'image' => 'https://images.pexels.com/photos/1028741/pexels-photo-1028741.jpeg',
                'color' => 'bg-pink-100 text-pink-600',
                'is_active' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($categories as $category) {
            OfferCategory::create($category);
        }
    }
}
