<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;

class UpdateOfferDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * هذا الـ Seeder يقوم بتحديث تواريخ العروض المنتهية وتواريخ صلاحية المنتجات
     */
    public function run(): void
    {
        $this->command->info('🔄 جاري تحديث تواريخ العروض...');
        
        // تحديث تواريخ جدول offers
        $offers = Offer::all();
        $updatedOffersCount = 0;
        
        foreach ($offers as $offer) {
            // التحقق من أن التاريخ قد انتهى أو سينتهي قريباً (خلال يوم واحد)
            $endDate = Carbon::parse($offer->end_date);
            
            if ($endDate->isPast() || $endDate->diffInDays(now()) < 1) {
                // تحديث تواريخ البدء والانتهاء
                $offer->start_date = Carbon::now();
                
                // تحديد مدة العرض بناءً على is_limited_time
                $daysToAdd = $offer->is_limited_time ? rand(1, 3) : rand(4, 7);
                $offer->end_date = Carbon::now()->addDays($daysToAdd);
                
                $offer->save();
                $updatedOffersCount++;
                
                $this->command->info("✅ تم تحديث العرض: {$offer->title} - ينتهي بعد {$daysToAdd} أيام");
            }
        }
        
        $this->command->info("📊 تم تحديث {$updatedOffersCount} عرض من أصل {$offers->count()}");
        
        // تحديث تواريخ صلاحية المنتجات (products مع original_price - منتجات العروض)
        $this->command->info('🔄 جاري تحديث تواريخ صلاحية منتجات العروض...');
        
        $offerProducts = Product::whereNotNull('original_price')
            ->whereNotNull('expiry_date')
            ->get();
        
        $updatedProductsCount = 0;
        
        foreach ($offerProducts as $product) {
            $expiryDate = Carbon::parse($product->expiry_date);
            
            // التحقق من أن تاريخ الصلاحية قد انتهى أو قريب من الانتهاء
            if ($expiryDate->isPast() || $expiryDate->diffInDays(now()) < 1) {
                // تحديث تاريخ الصلاحية بناءً على نوع المنتج
                if ($product->is_fresh) {
                    // المنتجات الطازجة: 1-7 أيام
                    $daysToAdd = rand(1, 7);
                } else {
                    // المنتجات غير الطازجة: 30-365 يوم
                    $daysToAdd = rand(30, 365);
                }
                
                $product->expiry_date = Carbon::now()->addDays($daysToAdd);
                $product->save();
                $updatedProductsCount++;
                
                $this->command->info("✅ تم تحديث منتج: {$product->name} - صالح لـ {$daysToAdd} يوم");
            }
        }
        
        $this->command->info("📊 تم تحديث {$updatedProductsCount} منتج من أصل {$offerProducts->count()}");
        
        // إضافة عروض جديدة إذا كان عدد العروض النشطة قليل
        $activeOffersCount = Offer::where('is_active', true)
            ->where('end_date', '>', now())
            ->count();
        
        if ($activeOffersCount < 5) {
            $this->command->info('ℹ️ عدد العروض النشطة قليل، سيتم إضافة عروض جديدة...');
            $this->command->call('db:seed', ['--class' => 'OfferSeeder']);
        }
        
        $this->command->info('✨ تم تحديث جميع التواريخ بنجاح!');
    }
}

