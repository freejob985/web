<?php

namespace Database\Seeders;

use App\Models\ContactMethod;
use Illuminate\Database\Seeder;

class ContactMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contactMethods = [
            [
                'name' => 'رقم الهاتف',
                'icon' => 'fas fa-phone',
                'value' => '+965 1234 5678',
                'link' => 'tel:+9651234567',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'البريد الإلكتروني',
                'icon' => 'fas fa-envelope',
                'value' => 'info@example.com',
                'link' => 'mailto:info@example.com',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'واتساب',
                'icon' => 'fab fa-whatsapp',
                'value' => '+965 1234 5678',
                'link' => 'https://wa.me/9651234567',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'العنوان',
                'icon' => 'fas fa-map-marker-alt',
                'value' => 'الكويت، مدينة الكويت',
                'link' => 'https://maps.google.com/?q=Kuwait+City',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($contactMethods as $method) {
            ContactMethod::create($method);
        }
    }
}