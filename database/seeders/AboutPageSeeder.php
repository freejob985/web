<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutPage;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section
        AboutPage::create([
            'section' => AboutPage::SECTION_HERO,
            'title' => 'من نحن',
            'content' => 'إنجب - منصة التسوق الإلكتروني الرائدة في الكويت، نقدم لك أفضل المنتجات الغذائية والاستهلاكية من موردين موثوقين مع ضمان الجودة والطزاجة.',
            'image' => 'about-pages/hero-image.jpg',
            'is_active' => true,
            'order' => 1,
            'meta_title' => 'من نحن - إنجب',
            'meta_description' => 'تعرف على إنجب، منصة التسوق الإلكتروني الرائدة في الكويت',
            'meta_keywords' => 'إنجب, تسوق إلكتروني, الكويت, منتجات غذائية'
        ]);

        // Story Section
        AboutPage::create([
            'section' => AboutPage::SECTION_STORY,
            'title' => 'قصتنا',
            'content' => 'بدأت رحلة إنجب في عام 2024 بهدف واحد: جعل التسوق الغذائي أسهل وأكثر موثوقية للعائلات الكويتية. نحن نؤمن بأن كل عائلة تستحق الحصول على منتجات طازجة وعالية الجودة بأسعار مناسبة.

من خلال شبكة واسعة من الموردين الموثوقين، نضمن وصول المنتجات الطازجة إلى منازلكم في أسرع وقت ممكن. نحن نعمل على ربط المزارعين والموردين المحليين بالعملاء مباشرة، مما يضمن جودة المنتجات وأسعاراً عادلة للجميع.

اليوم، إنجب هو الوجهة الأولى للتسوق الغذائي في الكويت، حيث نخدم آلاف العائلات يومياً ونواصل النمو والتطوير لخدمتكم بشكل أفضل.',
            'image' => 'about-pages/story-image.jpg',
            'is_active' => true,
            'order' => 1,
            'meta_title' => 'قصتنا - إنجب',
            'meta_description' => 'تعرف على قصة إنجب وكيف بدأت رحلتنا في خدمة المجتمع الكويتي',
            'meta_keywords' => 'قصة إنجب, تاريخ الشركة, رحلة النجاح'
        ]);

        // Values Section - Multiple values
        $values = [
            [
                'title' => 'الجودة والموثوقية',
                'description' => 'نضمن جودة جميع المنتجات من خلال شراكاتنا مع الموردين المعتمدين',
                'icon' => 'shield',
                'color' => 'blue'
            ],
            [
                'title' => 'التوصيل السريع',
                'description' => 'نوصل طلباتكم خلال 24-48 ساعة مع ضمان الطزاجة',
                'icon' => 'truck',
                'color' => 'green'
            ],
            [
                'title' => 'خدمة العملاء',
                'description' => 'فريق خدمة عملاء متخصص ومتاح 24/7 لمساعدتكم',
                'icon' => 'heart',
                'color' => 'red'
            ],
            [
                'title' => 'الأسعار العادلة',
                'description' => 'نقدم أفضل الأسعار من خلال التعامل المباشر مع الموردين',
                'icon' => 'target',
                'color' => 'orange'
            ],
            [
                'title' => 'الاستدامة',
                'description' => 'نؤمن بالاستدامة ونشجع المنتجات المحلية والطبيعية',
                'icon' => 'leaf',
                'color' => 'green'
            ],
            [
                'title' => 'الابتكار',
                'description' => 'نستخدم أحدث التقنيات لتحسين تجربة التسوق',
                'icon' => 'zap',
                'color' => 'purple'
            ]
        ];

        foreach ($values as $index => $value) {
            AboutPage::create([
                'section' => AboutPage::SECTION_VALUES,
                'title' => $value['title'],
                'content' => $value['description'],
                'is_active' => true,
                'order' => $index + 1,
                'extra_data' => [
                    'icon' => $value['icon'],
                    'color' => $value['color']
                ]
            ]);
        }

        // Statistics Section - Multiple statistics
        $statistics = [
            [
                'number' => '50,000+',
                'label' => 'عميل راضٍ',
                'icon' => 'users',
                'color' => 'blue'
            ],
            [
                'number' => '1,200+',
                'label' => 'مورد موثوق',
                'icon' => 'store',
                'color' => 'green'
            ],
            [
                'number' => '15,000+',
                'label' => 'منتج متاح',
                'icon' => 'package',
                'color' => 'purple'
            ],
            [
                'number' => '99.5%',
                'label' => 'معدل الرضا',
                'icon' => 'star',
                'color' => 'yellow'
            ]
        ];

        foreach ($statistics as $index => $stat) {
            AboutPage::create([
                'section' => AboutPage::SECTION_STATISTICS,
                'title' => $stat['label'],
                'content' => $stat['number'],
                'is_active' => true,
                'order' => $index + 1,
                'extra_data' => [
                    'number' => $stat['number'],
                    'icon' => $stat['icon'],
                    'color' => $stat['color']
                ]
            ]);
        }

        // Team Section - Multiple team members
        $teamMembers = [
            [
                'name' => 'أحمد محمد',
                'position' => 'المدير التنفيذي',
                'description' => 'خبرة 15 عام في التجارة الإلكترونية',
                'image' => 'about-pages/team/ahmed.jpg'
            ],
            [
                'name' => 'فاطمة أحمد',
                'position' => 'مديرة العمليات',
                'description' => 'متخصصة في إدارة سلسلة التوريد',
                'image' => 'about-pages/team/fatima.jpg'
            ],
            [
                'name' => 'محمد علي',
                'position' => 'مدير التكنولوجيا',
                'description' => 'خبير في تطوير المنصات الرقمية',
                'image' => 'about-pages/team/mohammed.jpg'
            ],
            [
                'name' => 'نورا سالم',
                'position' => 'مديرة خدمة العملاء',
                'description' => 'متخصصة في تجربة العملاء',
                'image' => 'about-pages/team/nora.jpg'
            ]
        ];

        foreach ($teamMembers as $index => $member) {
            AboutPage::create([
                'section' => AboutPage::SECTION_TEAM,
                'title' => $member['name'],
                'content' => $member['description'],
                'image' => $member['image'],
                'is_active' => true,
                'order' => $index + 1,
                'extra_data' => [
                    'position' => $member['position']
                ]
            ]);
        }

        // Mission Section
        AboutPage::create([
            'section' => AboutPage::SECTION_MISSION,
            'title' => 'رسالتنا',
            'content' => 'نهدف إلى جعل التسوق الغذائي تجربة سهلة ومريحة للعائلات الكويتية، من خلال توفير منتجات طازجة وعالية الجودة بأسعار مناسبة، مع ضمان التوصيل السريع والموثوق.',
            'is_active' => true,
            'order' => 1,
            'meta_title' => 'رسالتنا - إنجب',
            'meta_description' => 'تعرف على رسالة إنجب وأهدافنا في خدمة المجتمع الكويتي',
            'meta_keywords' => 'رسالة إنجب, أهداف الشركة, خدمة المجتمع'
        ]);
    }
}
