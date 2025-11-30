<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'خضروات وفواكه طازجة يومياً',
                'subtitle' => 'جودة عالية وأسعار مناسبة للجميع',
                'description' => 'احصل على أفضل الخضروات والفواكه الطازجة مباشرة من المزارع إلى منزلك',
                'button_text' => 'تسوق الخضروات',
                'button_url' => '/categories/fruits-vegetables',
                'image' => null,
                'background_image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                'badge' => 'طازج يومياً',
                'gradient_color' => 'from-green-600 to-emerald-600',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'عروض هائلة على منتجات الألبان',
                'subtitle' => 'خصومات تصل إلى 40% على جميع المنتجات',
                'description' => 'اكتشف تشكيلة واسعة من منتجات الألبان الطازجة والمعلبة بأفضل الأسعار',
                'button_text' => 'اطلع على العروض',
                'button_url' => '/offers',
                'image' => null,
                'background_image' => 'https://images.pexels.com/photos/8064204/pexels-photo-8064204.jpeg',
                'badge' => 'خصم 40%',
                'gradient_color' => 'from-blue-500 to-cyan-500',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'اللحوم الطازجة والمجمدة',
                'subtitle' => 'جودة مضمونة وحفظ آمن',
                'description' => 'تشكيلة متنوعة من اللحوم الطازجة والمجمدة مع ضمان الجودة والنظافة',
                'button_text' => 'تصفح اللحوم',
                'button_url' => '/categories/meat-poultry',
                'image' => null,
                'background_image' => 'https://images.pexels.com/photos/19352815/pexels-photo-19352815.jpeg',
                'badge' => 'جودة مضمونة',
                'gradient_color' => 'from-red-500 to-rose-500',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'منتجات طبيعية وعضوية',
                'subtitle' => 'صحة أفضل لك ولعائلتك',
                'description' => 'اكتشف تشكيلة واسعة من المنتجات الطبيعية والعضوية الخالية من المواد الكيميائية',
                'button_text' => 'تسوق المنتجات الطبيعية',
                'button_url' => '/categories/organic',
                'image' => null,
                'background_image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                'badge' => 'طبيعي 100%',
                'gradient_color' => 'from-green-500 to-teal-500',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'توصيل مجاني للطلبات الكبيرة',
                'subtitle' => 'توفير في التوصيل مع كل طلب',
                'description' => 'احصل على توصيل مجاني للطلبات التي تزيد عن 15 دينار في جميع أنحاء الكويت',
                'button_text' => 'تسوق الآن',
                'button_url' => '/categories',
                'image' => null,
                'background_image' => 'https://images.pexels.com/photos/4393667/pexels-photo-4393667.jpeg',
                'badge' => 'توصيل مجاني',
                'gradient_color' => 'from-purple-500 to-pink-500',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
