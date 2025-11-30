<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main Categories
        $categories = [
            [
                'name_ar' => 'خضروات وفواكه',
                'name_en' => 'Fruits & Vegetables',
                'description_ar' => 'أفضل الخضروات والفواكه الطازجة',
                'description_en' => 'Fresh fruits and vegetables',
                'icon' => 'fas fa-apple-alt',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name_ar' => 'منتجات الألبان',
                'name_en' => 'Dairy Products',
                'description_ar' => 'منتجات الألبان الطازجة',
                'description_en' => 'Fresh dairy products',
                'icon' => 'fas fa-milk',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name_ar' => 'لحوم ودواجن',
                'name_en' => 'Meat & Poultry',
                'description_ar' => 'لحوم ودواجن طازجة',
                'description_en' => 'Fresh meat and poultry',
                'icon' => 'fas fa-drumstick-bite',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'name_ar' => 'مخبوزات',
                'name_en' => 'Bakery',
                'description_ar' => 'مخبوزات طازجة يومياً',
                'description_en' => 'Fresh daily baked goods',
                'icon' => 'fas fa-bread-slice',
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'name_ar' => 'مجمدات',
                'name_en' => 'Frozen Foods',
                'description_ar' => 'منتجات مجمدة عالية الجودة',
                'description_en' => 'High quality frozen products',
                'icon' => 'fas fa-snowflake',
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'name_ar' => 'منظفات ومنتجات التنظيف',
                'name_en' => 'Cleaning Products',
                'description_ar' => 'منتجات التنظيف والعناية بالمنزل',
                'description_en' => 'Cleaning and home care products',
                'icon' => 'fas fa-spray-can',
                'sort_order' => 6,
                'is_active' => true
            ],
            [
                'name_ar' => 'منتجات الأطفال',
                'name_en' => 'Baby Care',
                'description_ar' => 'منتجات العناية بالأطفال',
                'description_en' => 'Baby care products',
                'icon' => 'fas fa-baby',
                'sort_order' => 7,
                'is_active' => true
            ],
            [
                'name_ar' => 'أدوات الطبخ',
                'name_en' => 'Cooking Tools',
                'description_ar' => 'أدوات الطبخ والمطبخ',
                'description_en' => 'Cooking and kitchen tools',
                'icon' => 'fas fa-utensils',
                'sort_order' => 8,
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            
            // Add subcategories for each category
            $subcategories = $this->getSubcategoriesForCategory($category->name_ar);
            foreach ($subcategories as $index => $subcategoryData) {
                $subcategoryData['category_id'] = $category->id;
                $subcategoryData['slug'] = \Illuminate\Support\Str::slug($subcategoryData['name_ar']) . '-' . $category->id . '-' . $index;
                Subcategory::create($subcategoryData);
            }
        }
    }

    private function getSubcategoriesForCategory($categoryName)
    {
        $subcategories = [
            'خضروات وفواكه' => [
                ['name_ar' => 'خضروات طازجة', 'name_en' => 'Fresh Vegetables', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'فواكه طازجة', 'name_en' => 'Fresh Fruits', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'خضروات مجمدة', 'name_en' => 'Frozen Vegetables', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'فواكه مجمدة', 'name_en' => 'Frozen Fruits', 'sort_order' => 4, 'is_active' => true]
            ],
            'منتجات الألبان' => [
                ['name_ar' => 'حليب ومشتقاته', 'name_en' => 'Milk & Dairy', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'جبن', 'name_en' => 'Cheese', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'زبدة ومرجرين', 'name_en' => 'Butter & Margarine', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'لبن رائب', 'name_en' => 'Yogurt', 'sort_order' => 4, 'is_active' => true]
            ],
            'لحوم ودواجن' => [
                ['name_ar' => 'لحوم حمراء', 'name_en' => 'Red Meat', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'دواجن', 'name_en' => 'Poultry', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'أسماك', 'name_en' => 'Fish', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'لحوم مجهزة', 'name_en' => 'Processed Meat', 'sort_order' => 4, 'is_active' => true]
            ],
            'مخبوزات' => [
                ['name_ar' => 'خبز', 'name_en' => 'Bread', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'حلويات', 'name_en' => 'Sweets', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'كيك وتورت', 'name_en' => 'Cakes & Tarts', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'معجنات', 'name_en' => 'Pastries', 'sort_order' => 4, 'is_active' => true]
            ],
            'مجمدات' => [
                ['name_ar' => 'لحوم مجمدة', 'name_en' => 'Frozen Meat', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'خضروات مجمدة', 'name_en' => 'Frozen Vegetables', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'وجبات جاهزة', 'name_en' => 'Ready Meals', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'آيس كريم', 'name_en' => 'Ice Cream', 'sort_order' => 4, 'is_active' => true]
            ],
            'منظفات ومنتجات التنظيف' => [
                ['name_ar' => 'منظفات الأرضيات', 'name_en' => 'Floor Cleaners', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'منظفات المطبخ', 'name_en' => 'Kitchen Cleaners', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'منظفات الحمام', 'name_en' => 'Bathroom Cleaners', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'منظفات الغسيل', 'name_en' => 'Laundry Detergents', 'sort_order' => 4, 'is_active' => true]
            ],
            'منتجات الأطفال' => [
                ['name_ar' => 'حفاضات', 'name_en' => 'Diapers', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'طعام الأطفال', 'name_en' => 'Baby Food', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'منتجات العناية', 'name_en' => 'Care Products', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'ألعاب آمنة', 'name_en' => 'Safe Toys', 'sort_order' => 4, 'is_active' => true]
            ],
            'أدوات الطبخ' => [
                ['name_ar' => 'أواني طبخ', 'name_en' => 'Cookware', 'sort_order' => 1, 'is_active' => true],
                ['name_ar' => 'أدوات المطبخ', 'name_en' => 'Kitchen Tools', 'sort_order' => 2, 'is_active' => true],
                ['name_ar' => 'أطباق وأكواب', 'name_en' => 'Dishes & Cups', 'sort_order' => 3, 'is_active' => true],
                ['name_ar' => 'أجهزة مطبخ', 'name_en' => 'Kitchen Appliances', 'sort_order' => 4, 'is_active' => true]
            ]
        ];

        return $subcategories[$categoryName] ?? [];
    }
}
