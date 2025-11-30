<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'استفسار عن المنتجات',
            'شكوى في الخدمة',
            'طلب استرداد',
            'اقتراح تحسين',
            'مشكلة في الطلب',
            'استفسار عن الشحن',
            'طلب مساعدة فنية',
            'تقييم الخدمة',
            'استفسار عن الكوبونات',
            'مشكلة في الدفع'
        ];

        $messages = [
            'أريد الاستفسار عن توفر منتج معين في المتجر',
            'واجهت مشكلة في عملية الدفع، يرجى المساعدة',
            'طلب استرداد مبلغ الطلب رقم 12345',
            'اقتراح إضافة منتجات جديدة للمتجر',
            'مشكلة في توصيل الطلب، تأخر عن الموعد المحدد',
            'استفسار عن تكلفة الشحن للمنطقة الخاصة بي',
            'طلب مساعدة في استخدام الموقع',
            'تقييم إيجابي للخدمة المقدمة',
            'استفسار عن كيفية استخدام الكوبونات',
            'مشكلة في تسجيل الدخول للموقع'
        ];

        $names = [
            'أحمد محمد', 'فاطمة علي', 'محمد عبدالله', 'نورا سالم', 'خالد أحمد',
            'مريم حسن', 'عبدالرحمن يوسف', 'سارة محمد', 'علي خالد', 'هند عبدالعزيز',
            'يوسف أحمد', 'ريم محمد', 'عبدالله سالم', 'نور الدين', 'مها علي',
            'سعد محمد', 'لينا أحمد', 'عمر خالد', 'فهد عبدالله', 'نورا محمد'
        ];

        $emails = [
            'ahmed@example.com', 'fatima@example.com', 'mohammed@example.com',
            'nora@example.com', 'khalid@example.com', 'mariam@example.com',
            'abdulrahman@example.com', 'sara@example.com', 'ali@example.com',
            'hind@example.com', 'youssef@example.com', 'reem@example.com',
            'abdullah@example.com', 'nour@example.com', 'maha@example.com',
            'saad@example.com', 'lina@example.com', 'omar@example.com',
            'fahad@example.com', 'nora2@example.com'
        ];

        $phones = [
            '+965 12345678', '+965 23456789', '+965 34567890', '+965 45678901',
            '+965 56789012', '+965 67890123', '+965 78901234', '+965 89012345',
            '+965 90123456', '+965 01234567', '+965 11111111', '+965 22222222',
            '+965 33333333', '+965 44444444', '+965 55555555', '+965 66666666',
            '+965 77777777', '+965 88888888', '+965 99999999', '+965 00000000'
        ];

        $statuses = ['new', 'read', 'replied', 'closed'];
        $statusWeights = [30, 25, 25, 20]; // 30% new, 25% read, 25% replied, 20% closed

        for ($i = 0; $i < 50; $i++) {
            $status = $this->getWeightedRandomStatus($statuses, $statusWeights);
            
            $contactMessage = ContactMessage::create([
                'name' => $names[array_rand($names)],
                'email' => $emails[array_rand($emails)],
                'phone' => rand(0, 10) > 3 ? $phones[array_rand($phones)] : null,
                'subject' => $subjects[array_rand($subjects)],
                'message' => $messages[array_rand($messages)] . ' ' . 'هذه رسالة تجريبية لاختبار نظام التواصل مع العملاء.',
                'status' => $status,
                'admin_notes' => rand(0, 10) > 7 ? 'ملاحظة إدارية: تم التواصل مع العميل' : null,
                'read_at' => $status !== 'new' ? now()->subDays(rand(1, 30)) : null,
                'replied_at' => in_array($status, ['replied', 'closed']) ? now()->subDays(rand(1, 20)) : null,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 30))
            ]);

            // Update timestamps to be consistent
            if ($contactMessage->read_at && $contactMessage->read_at < $contactMessage->created_at) {
                $contactMessage->update(['read_at' => $contactMessage->created_at]);
            }
            
            if ($contactMessage->replied_at && $contactMessage->replied_at < $contactMessage->created_at) {
                $contactMessage->update(['replied_at' => $contactMessage->created_at]);
            }
        }

        // Create some recent messages for testing
        for ($i = 0; $i < 10; $i++) {
            ContactMessage::create([
                'name' => $names[array_rand($names)],
                'email' => $emails[array_rand($emails)],
                'phone' => rand(0, 10) > 2 ? $phones[array_rand($phones)] : null,
                'subject' => $subjects[array_rand($subjects)],
                'message' => $messages[array_rand($messages)] . ' ' . 'رسالة جديدة من العميل.',
                'status' => 'new',
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Get a random status based on weights
     */
    private function getWeightedRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($statuses as $index => $status) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $status;
            }
        }
        
        return $statuses[0]; // fallback
    }
}
