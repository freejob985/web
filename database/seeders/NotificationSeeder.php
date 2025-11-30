<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendor = Vendor::first();
        if ($vendor) {
            // Create test notifications for the first vendor
            Notification::create([
                'vendor_id' => $vendor->id,
                'type' => 'order',
                'title' => 'طلب جديد',
                'message' => 'طلب جديد من أحمد محمد بقيمة 45 دينار',
                'data' => ['order_id' => 123, 'total' => 45],
                'icon' => 'fas fa-shopping-cart',
                'color' => 'success',
                'is_read' => false
            ]);
            
            Notification::create([
                'vendor_id' => $vendor->id,
                'type' => 'stock',
                'title' => 'نفاد المخزون',
                'message' => 'المنتج "تفاح أحمر" أوشك على النفاد',
                'data' => ['product_name' => 'تفاح أحمر'],
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'warning',
                'is_read' => false
            ]);
            
            Notification::create([
                'vendor_id' => $vendor->id,
                'type' => 'review',
                'title' => 'تقييم جديد',
                'message' => 'تقييم 5 نجوم لمنتج "خيار طازج"',
                'data' => ['product_name' => 'خيار طازج', 'rating' => 5],
                'icon' => 'fas fa-star',
                'color' => 'info',
                'is_read' => true
            ]);
            
            $this->command->info('Created 3 test notifications for vendor: ' . $vendor->id);
        } else {
            $this->command->warn('No vendors found');
        }
    }
}
