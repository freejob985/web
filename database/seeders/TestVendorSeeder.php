<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class TestVendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::create([
            'name' => 'Test Vendor',
            'email' => 'vendor@test.com',
            'password' => Hash::make('password123'),
            'phone' => '1234567890',
            'business_name' => 'Test Company',
            'business_type' => 'Trade',
            'commercial_record' => 'CR123456',
            'tax_number' => 'TAX123456',
            'bank_account' => '1234567890',
            'bank_name' => 'Test Bank',
            'address' => 'Test Address',
            'city' => 'Kuwait',
            'governorate' => 'Kuwait',
            'business_categories' => ['food', 'drinks'],
            'status' => 'approved',
            'is_active' => true,
        ]);
    }
}
