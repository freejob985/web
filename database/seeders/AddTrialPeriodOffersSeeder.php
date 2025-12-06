<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AddTrialPeriodOffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * هذا الـ Seeder يقوم بإضافة مدة تجريبية 6 أيام للعروض وإضافة منتجات العروض
     */
    public function run(): void
    {
        $this->command->info('🔄 جاري تحديث العروض وإضافة مدة تجريبية 6 أيام...');
        
        // التأكد من وجود أقسام العروض
        $this->ensureOfferCategories();
        
        // تحديث جميع العروض الموجودة
        $this->updateExistingOffers();
        
        // إضافة عروض جديدة إذا لم تكن موجودة
        $this->addNewOffers();
        
        // إضافة منتجات العروض
        $this->addOfferProducts();
        
        $this->command->info('✨ تم إكمال العملية بنجاح!');
    }
    
    /**
     * التأكد من وجود أقسام العروض
     */
    private function ensureOfferCategories(): void
    {
        $categories = OfferCategory::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('📁 جاري إنشاء أقسام العروض...');
            $this->call(OfferCategorySeeder::class);
        }
    }
    
    /**
     * تحديث جميع العروض الموجودة
     */
    private function updateExistingOffers(): void
    {
        $offers = Offer::all();
        $updatedCount = 0;
        
        foreach ($offers as $offer) {
            // تحديث جميع العروض لتكون نشطة مع مدة تجريبية 6 أيام
            $offer->start_date = Carbon::now();
            $offer->end_date = Carbon::now()->addDays(6);
            $offer->is_active = true;
            $offer->save();
            $updatedCount++;
            
            $this->command->info("✅ تم تحديث العرض: {$offer->title} - ينتهي بعد 6 أيام");
        }
        
        $this->command->info("📊 تم تحديث {$updatedCount} عرض من أصل {$offers->count()}");
    }
    
    /**
     * إضافة عروض جديدة إذا لم تكن موجودة
     */
    private function addNewOffers(): void
    {
        $activeOffersCount = Offer::where('is_active', true)
            ->where('end_date', '>', now())
            ->count();
        
        if ($activeOffersCount < 8) {
            $this->command->info('➕ جاري إضافة عروض جديدة...');
            
            $categories = OfferCategory::all();
            
            if ($categories->isEmpty()) {
                $this->command->warn('⚠️ لا توجد أقسام عروض. سيتم إنشاؤها...');
                $this->ensureOfferCategories();
                $categories = OfferCategory::all();
            }
            
            $offers = [
                [
                    'offer_category_id' => $categories->where('slug', 'vegetables-fruits')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 50% على الخضروات الطازجة',
                    'description' => 'احصل على خصم 50% على جميع الخضروات الطازجة من المزرعة مباشرة',
                    'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                    'original_price' => 10.00,
                    'new_price' => 5.00,
                    'discount_percentage' => 50,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => false,
                    'sort_order' => 1
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'vegetables-fruits')->first()?->id ?? $categories->first()->id,
                    'title' => 'عرض 2+1 على الفواكه الموسمية',
                    'description' => 'اشتر أي فاكهتين واحصل على الثالثة مجاناً',
                    'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                    'original_price' => 15.00,
                    'new_price' => 10.00,
                    'discount_percentage' => 33,
                    'discount_type' => 'buy_x_get_y',
                    'buy_quantity' => 2,
                    'get_quantity' => 1,
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => false,
                    'is_limited_time' => true,
                    'sort_order' => 2
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'dairy-products')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 30% على منتجات الألبان',
                    'description' => 'جبن طازج من أفضل المزارع بخصم 30%',
                    'image' => 'https://images.pexels.com/photos/8064204/pexels-photo-8064204.jpeg',
                    'original_price' => 20.00,
                    'new_price' => 14.00,
                    'discount_percentage' => 30,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => false,
                    'sort_order' => 3
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'meat-poultry')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 30% على اللحوم الطازجة',
                    'description' => 'لحوم طازجة من أفضل المزارع بخصم 30%',
                    'image' => 'https://images.pexels.com/photos/19352815/pexels-photo-19352815.jpeg',
                    'original_price' => 25.00,
                    'new_price' => 17.50,
                    'discount_percentage' => 30,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => false,
                    'sort_order' => 4
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'bakery')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 40% على المخبوزات الطازجة',
                    'description' => 'مخبوزات طازجة من الفرن مباشرة بخصم 40%',
                    'image' => 'https://images.pexels.com/photos/2680601/pexels-photo-2680601.jpeg',
                    'original_price' => 5.00,
                    'new_price' => 3.00,
                    'discount_percentage' => 40,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => false,
                    'sort_order' => 5
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'beverages')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 35% على العصائر الطبيعية',
                    'description' => 'عصائر طبيعية 100% بخصم 35%',
                    'image' => 'https://images.pexels.com/photos/1435735/pexels-photo-1435735.jpeg',
                    'original_price' => 12.00,
                    'new_price' => 7.80,
                    'discount_percentage' => 35,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => false,
                    'is_limited_time' => false,
                    'sort_order' => 6
                ],
                [
                    'offer_category_id' => $categories->where('slug', 'sweets')->first()?->id ?? $categories->first()->id,
                    'title' => 'خصم 45% على الحلويات الشرقية',
                    'description' => 'حلويات شرقية تقليدية بخصم 45%',
                    'image' => 'https://images.pexels.com/photos/1028741/pexels-photo-1028741.jpeg',
                    'original_price' => 22.00,
                    'new_price' => 12.10,
                    'discount_percentage' => 45,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => false,
                    'sort_order' => 7
                ],
                [
                    'offer_category_id' => $categories->first()->id,
                    'title' => 'عرض خاص على المنتجات المميزة',
                    'description' => 'عروض خاصة على أفضل المنتجات المميزة',
                    'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                    'original_price' => 18.00,
                    'new_price' => 12.00,
                    'discount_percentage' => 33,
                    'discount_type' => 'percentage',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(6),
                    'is_active' => true,
                    'is_featured' => true,
                    'is_limited_time' => true,
                    'sort_order' => 8
                ]
            ];
            
            foreach ($offers as $offerData) {
                // التحقق من عدم وجود عرض بنفس العنوان
                $existingOffer = Offer::where('title', $offerData['title'])->first();
                
                if (!$existingOffer) {
                    Offer::create($offerData);
                    $this->command->info("✅ تم إضافة عرض جديد: {$offerData['title']}");
                }
            }
        }
    }
    
    /**
     * إضافة منتجات العروض
     */
    private function addOfferProducts(): void
    {
        $this->command->info('🛍️ جاري إضافة منتجات العروض...');
        
        // الحصول على البيانات الأساسية
        $vendors = Vendor::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        
        if ($vendors->isEmpty() || $categories->isEmpty() || $subcategories->isEmpty() || $brands->isEmpty()) {
            $this->command->warn('⚠️ يرجى التأكد من وجود Vendors, Categories, Subcategories, و Brands قبل إضافة منتجات العروض');
            return;
        }
        
        // عدد المنتجات الحالية مع original_price
        $existingOfferProducts = Product::whereNotNull('original_price')->count();
        
        if ($existingOfferProducts < 20) {
            $this->command->info('➕ جاري إضافة منتجات عروض جديدة...');
            
            $offerProducts = [
                [
                    'name' => 'تفاح أحمر طازج - كيلو - عرض خاص',
                    'description' => 'تفاح أحمر طازج ولذيذ، مستورد من أجود المزارع. غني بالفيتامينات والألياف الطبيعية. عرض خاص لفترة محدودة!',
                    'price' => 1.000,
                    'original_price' => 1.500,
                    'stock' => 150,
                    'sku' => 'APPLE-RED-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/3746517/pexels-photo-3746517.jpeg']),
                    'origin' => 'تركيا',
                    'weight' => 1.000,
                    'unit' => 'كيلو',
                    'is_fresh' => true,
                    'is_featured' => true,
                    'is_active' => true,
                    'rating' => 4.8,
                    'reviews_count' => 156,
                    'sales_count' => 342,
                    'expiry_date' => Carbon::now()->addDays(7),
                ],
                [
                    'name' => 'خيار طازج محلي - كيلو - خصم 30%',
                    'description' => 'خيار طازج من المزارع المحلية الكويتية، مقرمش ومنعش، مثالي للسلطات والعصائر. خصم 30% لفترة محدودة!',
                    'price' => 0.560,
                    'original_price' => 0.800,
                    'stock' => 200,
                    'sku' => 'CUCUMBER-LOCAL-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/8805175/pexels-photo-8805175.jpeg']),
                    'origin' => 'الكويت',
                    'weight' => 1.000,
                    'unit' => 'كيلو',
                    'is_fresh' => true,
                    'is_featured' => false,
                    'is_active' => true,
                    'rating' => 4.9,
                    'reviews_count' => 89,
                    'sales_count' => 234,
                    'expiry_date' => Carbon::now()->addDays(5),
                ],
                [
                    'name' => 'طماطم كرزية طازجة - 500 جرام - عرض مميز',
                    'description' => 'طماطم كرزية صغيرة وحلوة المذاق، مثالية للسلطات والوجبات الخفيفة. عرض مميز لفترة محدودة!',
                    'price' => 0.840,
                    'original_price' => 1.400,
                    'stock' => 80,
                    'sku' => 'TOMATO-CHERRY-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/10763771/pexels-photo-10763771.jpeg']),
                    'origin' => 'لبنان',
                    'weight' => 0.500,
                    'unit' => 'علبة',
                    'is_fresh' => true,
                    'is_featured' => true,
                    'is_active' => true,
                    'rating' => 4.6,
                    'reviews_count' => 67,
                    'sales_count' => 156,
                    'expiry_date' => Carbon::now()->addDays(4),
                ],
                [
                    'name' => 'حليب طازج كامل الدسم - لتر - خصم 25%',
                    'description' => 'حليب طازج كامل الدسم من أجود المزارع المحلية، غني بالكالسيوم والبروتين. خصم 25%!',
                    'price' => 0.487,
                    'original_price' => 0.650,
                    'stock' => 200,
                    'sku' => 'MILK-FULL-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/248412/pexels-photo-248412.jpeg']),
                    'origin' => 'الكويت',
                    'weight' => 1.000,
                    'unit' => 'لتر',
                    'is_fresh' => true,
                    'is_featured' => true,
                    'is_active' => true,
                    'rating' => 4.8,
                    'reviews_count' => 245,
                    'sales_count' => 567,
                    'expiry_date' => Carbon::now()->addDays(3),
                ],
                [
                    'name' => 'جبن أبيض طازج - 250 جرام - عرض خاص',
                    'description' => 'جبن أبيض طازج وطري، مصنوع من حليب طبيعي 100%، مثالي للإفطار والسندويشات. عرض خاص!',
                    'price' => 1.260,
                    'original_price' => 2.000,
                    'stock' => 60,
                    'sku' => 'CHEESE-WHITE-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/773253/pexels-photo-773253.jpeg']),
                    'origin' => 'الدنمارك',
                    'weight' => 0.250,
                    'unit' => 'علبة',
                    'is_fresh' => true,
                    'is_featured' => false,
                    'is_active' => true,
                    'rating' => 4.4,
                    'reviews_count' => 92,
                    'sales_count' => 134,
                    'expiry_date' => Carbon::now()->addDays(7),
                ],
                [
                    'name' => 'دجاج طازج محلي - كيلو - خصم 30%',
                    'description' => 'دجاج طازج من المزارع المحلية، مربى بطريقة طبيعية وصحية، لحم طري ولذيذ. خصم 30%!',
                    'price' => 2.450,
                    'original_price' => 3.500,
                    'stock' => 45,
                    'sku' => 'CHICKEN-LOCAL-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/616354/pexels-photo-616354.jpeg']),
                    'origin' => 'الكويت',
                    'weight' => 1.000,
                    'unit' => 'كيلو',
                    'is_fresh' => true,
                    'is_featured' => true,
                    'is_active' => true,
                    'rating' => 4.9,
                    'reviews_count' => 167,
                    'sales_count' => 234,
                    'expiry_date' => Carbon::now()->addDays(2),
                ],
                [
                    'name' => 'خبز عربي طازج - 5 أرغفة - خصم 20%',
                    'description' => 'خبز عربي طازج ومخبوز يومياً، طري ولذيذ، مثالي لجميع الوجبات. خصم 20%!',
                    'price' => 0.400,
                    'original_price' => 0.500,
                    'stock' => 300,
                    'sku' => 'BREAD-ARABIC-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/209206/pexels-photo-209206.jpeg']),
                    'origin' => 'الكويت',
                    'weight' => 0.400,
                    'unit' => 'كيس',
                    'is_fresh' => true,
                    'is_featured' => false,
                    'is_active' => true,
                    'rating' => 4.6,
                    'reviews_count' => 234,
                    'sales_count' => 678,
                    'expiry_date' => Carbon::now()->addDays(2),
                ],
                [
                    'name' => 'عسل طبيعي - 500 جرام - خصم 50%',
                    'description' => 'عسل طبيعي وعالي الجودة، غني بمضادات الأكسدة والفيتامينات، مثالي للتحلية الطبيعية. خصم 50%!',
                    'price' => 6.000,
                    'original_price' => 15.000,
                    'stock' => 60,
                    'sku' => 'HONEY-NATURAL-OFFER-' . time(),
                    'image' => 'https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg',
                    'images' => json_encode(['https://images.pexels.com/photos/143133/pexels-photo-143133.jpeg']),
                    'origin' => 'لبنان',
                    'weight' => 0.500,
                    'unit' => 'زجاجة',
                    'is_fresh' => false,
                    'is_featured' => true,
                    'is_active' => true,
                    'rating' => 4.9,
                    'reviews_count' => 78,
                    'sales_count' => 123,
                    'expiry_date' => Carbon::now()->addDays(1095),
                ]
            ];
            
            $addedCount = 0;
            foreach ($offerProducts as $productData) {
                // التحقق من عدم وجود منتج بنفس الاسم
                $existingProduct = Product::where('name', $productData['name'])->first();
                
                if (!$existingProduct) {
                    try {
                        // تعيين vendor, category, subcategory, brand عشوائياً
                        $productData['vendor_id'] = $vendors->random()->id;
                        $productData['category_id'] = $categories->random()->id;
                        $productData['subcategory_id'] = $subcategories->random()->id;
                        $productData['brand_id'] = $brands->random()->id;
                        $productData['status'] = 'approved';
                        $productData['created_at'] = now();
                        $productData['updated_at'] = now();
                        
                        Product::create($productData);
                        $addedCount++;
                        $this->command->info("✅ تم إضافة منتج عرض: {$productData['name']}");
                    } catch (\Exception $e) {
                        $this->command->error("❌ خطأ في إضافة منتج: {$productData['name']} - {$e->getMessage()}");
                    }
                }
            }
            
            $this->command->info("📊 تم إضافة {$addedCount} منتج عرض جديد");
        } else {
            $this->command->info("ℹ️ يوجد بالفعل {$existingOfferProducts} منتج عرض، لا حاجة لإضافة المزيد");
        }
    }
}

