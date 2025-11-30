<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportChannel;

class SupportChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supportChannels = [
            [
                'title_ar' => 'خدمة العملاء',
                'title_en' => 'Customer Service',
                'description_ar' => 'للاستفسارات العامة ومساعدة العملاء',
                'description_en' => 'For general inquiries and customer assistance',
                'contact_info' => '+966 50 123 4567',
                'availability' => '24/7',
                'icon' => 'fas fa-headset',
                'color' => '#3B82F6',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title_ar' => 'الدعم الفني',
                'title_en' => 'Technical Support',
                'description_ar' => 'لحل المشاكل التقنية ومشاكل التطبيق',
                'description_en' => 'To solve technical problems and application issues',
                'contact_info' => 'tech@elite1market.com',
                'availability' => 'وص - 6 م',
                'icon' => 'fas fa-exclamation-triangle',
                'color' => '#EF4444',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'title_ar' => 'دعم الموردين',
                'title_en' => 'Vendor Support',
                'description_ar' => 'مساعدة خاصة للموردين والتجار',
                'description_en' => 'Special assistance for suppliers and traders',
                'contact_info' => 'vendors@elite1market.com',
                'availability' => 'وص - 5 م',
                'icon' => 'fas fa-store',
                'color' => '#10B981',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'title_ar' => 'الشكاوي والاقتراحات',
                'title_en' => 'Complaints and Suggestions',
                'description_ar' => 'لتقديم الشكاوي والاقتراحات',
                'description_en' => 'To submit complaints and suggestions',
                'contact_info' => 'feedback@elite1market.com',
                'availability' => 'دائماً متاح',
                'icon' => 'fas fa-file-alt',
                'color' => '#8B5CF6',
                'sort_order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($supportChannels as $channel) {
            SupportChannel::create($channel);
        }
    }
}
