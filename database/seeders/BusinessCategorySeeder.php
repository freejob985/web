<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'مطاعم',
                'description' => 'مطاعم وكافيهات',
                'icon' => 'fa-utensils',
            ],
            [
                'name' => 'سوبر ماركت',
                'description' => 'محلات البقالة والسوبر ماركت',
                'icon' => 'fa-shopping-cart',
            ],
            [
                'name' => 'ملابس',
                'description' => 'محلات الملابس والأزياء',
                'icon' => 'fa-tshirt',
            ],
            [
                'name' => 'إلكترونيات',
                'description' => 'أجهزة إلكترونية وكهربائية',
                'icon' => 'fa-laptop',
            ],
            [
                'name' => 'صيدليات',
                'description' => 'صيدليات ومستلزمات طبية',
                'icon' => 'fa-prescription-bottle-alt',
            ],
            [
                'name' => 'مستحضرات تجميل',
                'description' => 'منتجات العناية والتجميل',
                'icon' => 'fa-spa',
            ],
            [
                'name' => 'أثاث ومفروشات',
                'description' => 'أثاث منزلي ومفروشات',
                'icon' => 'fa-couch',
            ],
            [
                'name' => 'خدمات',
                'description' => 'خدمات متنوعة',
                'icon' => 'fa-concierge-bell',
            ],
        ];

        foreach ($categories as $index => $category) {
            BusinessCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }
}