<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question_ar' => 'كيف يمكنني تتبع طلبي؟',
                'answer_ar' => 'يمكنك تتبع طلبك من خلال رقم الطلبية في صفحة "تتبع الطلب" أو من خلال الرسائل النصية التي نرسلها لك.',
                'category' => 'orders',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'question_ar' => 'ما هي طرق الدفع المتاحة؟',
                'answer_ar' => 'نقبل الدفع نقداً عند الاستلام، وبطاقات الائتمان، وKNET، والمحفظة الإلكترونية.',
                'category' => 'payment',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'question_ar' => 'كم تبلغ رسوم التوصيل؟',
                'answer_ar' => 'رسوم التوصيل تبدأ من 2 دينار كويتي. التوصيل مجاني للطلبات التي تزيد عن 25 دينار كويتي.',
                'category' => 'delivery',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'question_ar' => 'ما هي أوقات التوصيل؟',
                'answer_ar' => 'نوفر التوصيل على مدار الساعة. يمكنك اختيار الوقت المناسب لك من خلال التطبيق.',
                'category' => 'delivery',
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'question_ar' => 'كيف يمكنني إلغاء طلبي؟',
                'answer_ar' => 'يمكنك إلغاء طلبك خلال 30 دقيقة من وقت الطلب من خلال التطبيق أو بالاتصال بنا.',
                'category' => 'orders',
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'question_ar' => 'هل المنتجات طازجة؟',
                'answer_ar' => 'نعم، جميع منتجاتنا طازجة ومختارة بعناية من أفضل الموردين المحليين.',
                'category' => 'products',
                'sort_order' => 6,
                'is_active' => true
            ],
            [
                'question_ar' => 'كيف يمكنني التواصل مع خدمة العملاء؟',
                'answer_ar' => 'يمكنك التواصل معنا عبر الهاتف: +965 1234 5678، أو عبر البريد الإلكتروني: support@elite1.com',
                'category' => 'support',
                'sort_order' => 7,
                'is_active' => true
            ],
            [
                'question_ar' => 'هل يمكنني تعديل طلبي بعد وضعه؟',
                'answer_ar' => 'يمكنك تعديل طلبك خلال 15 دقيقة من وقت الطلب. بعد ذلك، يرجى الاتصال بنا.',
                'category' => 'orders',
                'sort_order' => 8,
                'is_active' => true
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}