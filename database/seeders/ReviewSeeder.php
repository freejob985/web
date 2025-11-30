<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\User;
use App\Models\ProductReview;
use App\Models\VendorReview;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المنتجات والمستخدمين والموردين
        $products = Product::take(10)->get();
        $users = User::take(20)->get();
        $vendors = Vendor::take(5)->get();

        if ($products->isEmpty() || $users->isEmpty() || $vendors->isEmpty()) {
            $this->command->warn('لا توجد بيانات كافية لإنشاء التقييمات. تأكد من وجود منتجات ومستخدمين وموردين.');
            return;
        }

        // إنشاء تقييمات المنتجات
        foreach ($products as $product) {
            $reviewsCount = rand(3, 8);
            
            for ($i = 0; $i < $reviewsCount; $i++) {
                $user = $users->random();
                $rating = rand(1, 5);
                
                // التحقق من عدم وجود تقييم سابق
                $existingReview = ProductReview::where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if ($existingReview) {
                    continue; // تخطي إذا كان التقييم موجود
                }
                
                // تعليقات متنوعة
                $comments = [
                    'منتج ممتاز، أنصح به بشدة',
                    'جودة عالية وسعر مناسب',
                    'توصيل سريع ومنتج طازج',
                    'لم يعجبني المنتج كثيراً',
                    'منتج جيد ولكن يمكن تحسينه',
                    'ممتاز، سأطلبه مرة أخرى',
                    'جودة متوسطة',
                    'منتج رائع، شكراً لكم',
                    'لم يصل المنتج بالشكل المطلوب',
                    'منتج جيد جداً، أنصح به'
                ];

                ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'vendor_id' => $product->vendor_id,
                    'rating' => $rating,
                    'comment' => $comments[array_rand($comments)],
                    'is_approved' => true,
                    'is_anonymous' => rand(0, 1) == 1,
                    'images' => null,
                    'created_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        // إنشاء تقييمات الموردين
        foreach ($vendors as $vendor) {
            $reviewsCount = rand(5, 12);
            
            for ($i = 0; $i < $reviewsCount; $i++) {
                $user = $users->random();
                $rating = rand(1, 5);
                
                // التحقق من عدم وجود تقييم سابق
                $existingReview = VendorReview::where('vendor_id', $vendor->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if ($existingReview) {
                    continue; // تخطي إذا كان التقييم موجود
                }
                
                // تعليقات متنوعة للموردين
                $comments = [
                    'خدمة ممتازة وتوصيل سريع',
                    'منتجات طازجة وجودة عالية',
                    'توصيل في الوقت المحدد',
                    'خدمة عملاء ممتازة',
                    'منتجات متنوعة وأسعار مناسبة',
                    'توصيل مجاني سريع',
                    'منتجات عالية الجودة',
                    'خدمة رائعة، أنصح بهم',
                    'توصيل بطيء قليلاً',
                    'منتجات جيدة ولكن يمكن تحسين الخدمة'
                ];

                VendorReview::create([
                    'vendor_id' => $vendor->id,
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'comment' => $comments[array_rand($comments)],
                    'is_approved' => true,
                    'is_anonymous' => rand(0, 1) == 1,
                    'images' => null,
                    'created_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        // تحديث إحصائيات التقييمات للمنتجات
        foreach ($products as $product) {
            $reviews = $product->reviews()->approved()->get();
            if ($reviews->count() > 0) {
                $averageRating = $reviews->avg('rating');
                $product->update([
                    'rating' => round($averageRating, 2),
                    'reviews_count' => $reviews->count()
                ]);
            }
        }

        // تحديث إحصائيات التقييمات للموردين
        foreach ($vendors as $vendor) {
            $reviews = $vendor->reviews()->approved()->get();
            if ($reviews->count() > 0) {
                $averageRating = $reviews->avg('rating');
                $vendor->update([
                    'rating' => round($averageRating, 2),
                    'reviews_count' => $reviews->count()
                ]);
            }
        }

        $this->command->info('تم إنشاء ' . ProductReview::count() . ' تقييم منتج و ' . VendorReview::count() . ' تقييم مورد بنجاح!');
    }
}
