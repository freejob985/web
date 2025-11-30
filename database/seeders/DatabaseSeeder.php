<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            KuwaitDataSeeder::class,
            CategorySeeder::class,
            SliderSeeder::class,
            DemoSeeder::class,
            SimpleProductSeeder::class,
            ComprehensiveProductSeeder::class,
            ComprehensiveOfferSeeder::class,
            FaqSeeder::class,
            SettingsSeeder::class,
            MapSettingsSeeder::class,
            CouponSeeder::class,
            BusinessCategorySeeder::class,
            AboutPageSeeder::class,
            BrandSeeder::class,
            AdditionalVendorSeeder::class,
            SupportChannelSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}
