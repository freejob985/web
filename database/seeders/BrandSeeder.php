<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            'المراعي',
            'نادك',
            'العلالي',
            'الراجحي',
            'الزهرة',
            'الخليج',
            'الطازج',
            'البيت',
            'الطبيعة',
            'الذوق',
            'نستله',
            'كوكا كولا',
            'بيبسي',
            'كيت كات',
            'دانون',
            'فيري',
            'دوف',
            'بانتين',
            'أريال',
            'ديتول'
        ];

        foreach ($brands as $index => $brandName) {
            Brand::firstOrCreate(
                ['name' => $brandName],
                [
                    'name' => $brandName,
                    'is_active' => true,
                    'sort_order' => $index + 1
                ]
            );
        }

        $this->command->info('تم إنشاء ' . count($brands) . ' ماركة بنجاح!');
    }
}