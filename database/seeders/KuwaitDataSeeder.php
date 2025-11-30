<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KuwaitDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kuwait Governorates
        $governorates = [
            ['name_ar' => 'الكويت', 'name_en' => 'Kuwait', 'code' => 'KW', 'sort_order' => 1],
            ['name_ar' => 'الأحمدي', 'name_en' => 'Ahmadi', 'code' => 'AH', 'sort_order' => 2],
            ['name_ar' => 'حولي', 'name_en' => 'Hawalli', 'code' => 'HA', 'sort_order' => 3],
            ['name_ar' => 'الفروانية', 'name_en' => 'Farwaniya', 'code' => 'FA', 'sort_order' => 4],
            ['name_ar' => 'الجهراء', 'name_en' => 'Jahra', 'code' => 'JA', 'sort_order' => 5],
            ['name_ar' => 'مبارك الكبير', 'name_en' => 'Mubarak Al-Kabeer', 'code' => 'MU', 'sort_order' => 6],
        ];

        foreach ($governorates as $governorate) {
            Governorate::create($governorate);
        }

        // Kuwait Cities
        $cities = [
            // الكويت
            ['name_ar' => 'الكويت', 'name_en' => 'Kuwait City', 'code' => 'KWC', 'governorate_id' => 1, 'sort_order' => 1],
            ['name_ar' => 'دسمان', 'name_en' => 'Dasman', 'code' => 'DAS', 'governorate_id' => 1, 'sort_order' => 2],
            ['name_ar' => 'الشرق', 'name_en' => 'Sharq', 'code' => 'SHA', 'governorate_id' => 1, 'sort_order' => 3],
            ['name_ar' => 'الصالحية', 'name_en' => 'Salhiya', 'code' => 'SAL', 'governorate_id' => 1, 'sort_order' => 4],
            ['name_ar' => 'المرقاب', 'name_en' => 'Mirqab', 'code' => 'MIR', 'governorate_id' => 1, 'sort_order' => 5],
            ['name_ar' => 'القبلة', 'name_en' => 'Qibla', 'code' => 'QIB', 'governorate_id' => 1, 'sort_order' => 6],
            ['name_ar' => 'الوطية', 'name_en' => 'Watiya', 'code' => 'WAT', 'governorate_id' => 1, 'sort_order' => 7],
            ['name_ar' => 'الرقة', 'name_en' => 'Rigga', 'code' => 'RIG', 'governorate_id' => 1, 'sort_order' => 8],
            ['name_ar' => 'العديلية', 'name_en' => 'Adailiya', 'code' => 'ADA', 'governorate_id' => 1, 'sort_order' => 9],
            ['name_ar' => 'الفيحاء', 'name_en' => 'Faiha', 'code' => 'FAI', 'governorate_id' => 1, 'sort_order' => 10],

            // الأحمدي
            ['name_ar' => 'الأحمدي', 'name_en' => 'Ahmadi', 'code' => 'AHM', 'governorate_id' => 2, 'sort_order' => 1],
            ['name_ar' => 'الوفرة', 'name_en' => 'Wafra', 'code' => 'WAF', 'governorate_id' => 2, 'sort_order' => 2],
            ['name_ar' => 'الضباعية', 'name_en' => 'Dhabaiya', 'code' => 'DHA', 'governorate_id' => 2, 'sort_order' => 3],
            ['name_ar' => 'الزور', 'name_en' => 'Zour', 'code' => 'ZOU', 'governorate_id' => 2, 'sort_order' => 4],
            ['name_ar' => 'المنقف', 'name_en' => 'Minaqif', 'code' => 'MIN', 'governorate_id' => 2, 'sort_order' => 5],
            ['name_ar' => 'الرقة', 'name_en' => 'Rigga', 'code' => 'RIG2', 'governorate_id' => 2, 'sort_order' => 6],
            ['name_ar' => 'الوفرة', 'name_en' => 'Wafra', 'code' => 'WAF2', 'governorate_id' => 2, 'sort_order' => 7],

            // حولي
            ['name_ar' => 'حولي', 'name_en' => 'Hawalli', 'code' => 'HAW', 'governorate_id' => 3, 'sort_order' => 1],
            ['name_ar' => 'السالمية', 'name_en' => 'Salmiya', 'code' => 'SAL2', 'governorate_id' => 3, 'sort_order' => 2],
            ['name_ar' => 'الرميثية', 'name_en' => 'Rumaithiya', 'code' => 'RUM', 'governorate_id' => 3, 'sort_order' => 3],
            ['name_ar' => 'البدع', 'name_en' => 'Bidaa', 'code' => 'BID', 'governorate_id' => 3, 'sort_order' => 4],
            ['name_ar' => 'الزهراء', 'name_en' => 'Zahra', 'code' => 'ZAH', 'governorate_id' => 3, 'sort_order' => 5],
            ['name_ar' => 'المنصورية', 'name_en' => 'Mansouriya', 'code' => 'MAN', 'governorate_id' => 3, 'sort_order' => 6],
            ['name_ar' => 'الخالدية', 'name_en' => 'Khaldiya', 'code' => 'KHA', 'governorate_id' => 3, 'sort_order' => 7],
            ['name_ar' => 'الروضة', 'name_en' => 'Rawda', 'code' => 'RAW', 'governorate_id' => 3, 'sort_order' => 8],
            ['name_ar' => 'العديلية', 'name_en' => 'Adailiya', 'code' => 'ADA2', 'governorate_id' => 3, 'sort_order' => 9],
            ['name_ar' => 'الفيحاء', 'name_en' => 'Faiha', 'code' => 'FAI2', 'governorate_id' => 3, 'sort_order' => 10],

            // الفروانية
            ['name_ar' => 'الفروانية', 'name_en' => 'Farwaniya', 'code' => 'FAR', 'governorate_id' => 4, 'sort_order' => 1],
            ['name_ar' => 'الشدادية', 'name_en' => 'Shadadiya', 'code' => 'SHA2', 'governorate_id' => 4, 'sort_order' => 2],
            ['name_ar' => 'الرابية', 'name_en' => 'Rabiya', 'code' => 'RAB', 'governorate_id' => 4, 'sort_order' => 3],
            ['name_ar' => 'الخالدية', 'name_en' => 'Khaldiya', 'code' => 'KHA2', 'governorate_id' => 4, 'sort_order' => 4],
            ['name_ar' => 'الروضة', 'name_en' => 'Rawda', 'code' => 'RAW2', 'governorate_id' => 4, 'sort_order' => 5],
            ['name_ar' => 'العديلية', 'name_en' => 'Adailiya', 'code' => 'ADA3', 'governorate_id' => 4, 'sort_order' => 6],
            ['name_ar' => 'الفيحاء', 'name_en' => 'Faiha', 'code' => 'FAI3', 'governorate_id' => 4, 'sort_order' => 7],

            // الجهراء
            ['name_ar' => 'الجهراء', 'name_en' => 'Jahra', 'code' => 'JAH', 'governorate_id' => 5, 'sort_order' => 1],
            ['name_ar' => 'القصر', 'name_en' => 'Qasr', 'code' => 'QAS', 'governorate_id' => 5, 'sort_order' => 2],
            ['name_ar' => 'الوفرة', 'name_en' => 'Wafra', 'code' => 'WAF3', 'governorate_id' => 5, 'sort_order' => 3],
            ['name_ar' => 'الضباعية', 'name_en' => 'Dhabaiya', 'code' => 'DHA2', 'governorate_id' => 5, 'sort_order' => 4],
            ['name_ar' => 'الزور', 'name_en' => 'Zour', 'code' => 'ZOU2', 'governorate_id' => 5, 'sort_order' => 5],
            ['name_ar' => 'المنقف', 'name_en' => 'Minaqif', 'code' => 'MIN2', 'governorate_id' => 5, 'sort_order' => 6],

            // مبارك الكبير
            ['name_ar' => 'مبارك الكبير', 'name_en' => 'Mubarak Al-Kabeer', 'code' => 'MUB', 'governorate_id' => 6, 'sort_order' => 1],
            ['name_ar' => 'العديلية', 'name_en' => 'Adailiya', 'code' => 'ADA4', 'governorate_id' => 6, 'sort_order' => 2],
            ['name_ar' => 'الفيحاء', 'name_en' => 'Faiha', 'code' => 'FAI4', 'governorate_id' => 6, 'sort_order' => 3],
            ['name_ar' => 'الروضة', 'name_en' => 'Rawda', 'code' => 'RAW3', 'governorate_id' => 6, 'sort_order' => 4],
            ['name_ar' => 'الخالدية', 'name_en' => 'Khaldiya', 'code' => 'KHA3', 'governorate_id' => 6, 'sort_order' => 5],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
