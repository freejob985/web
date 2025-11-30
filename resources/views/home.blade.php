@extends('layouts.app')

@section('title', 'إيليت ون سوبر ماركت - الكويت')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="flex items-center mb-4">
                    <span class="bg-white/20 text-white border border-white/30 px-6 py-2 rounded-full text-lg flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        توصيل فوري
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    سوبر ماركت إيليت ون
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    أفضل المنتجات الغذائية والاستهلاكية في الكويت مع توصيل سريع وآمن
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('categories') }}" class="bg-white text-primary hover:bg-gray-100 text-lg px-8 py-4 rounded-lg font-medium transition-colors">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        تسوق الآن
                    </a>
                    <a href="{{ route('offers') }}" class="border border-white text-white hover:bg-white hover:text-primary text-lg px-8 py-4 rounded-lg font-medium transition-colors">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        اكتشف العروض
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-white/10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold">30</div>
                            <div class="text-sm opacity-80">دقيقة متوسط التوصيل</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">24/7</div>
                            <div class="text-sm opacity-80">خدمة على مدار الساعة</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">99%</div>
                            <div class="text-sm opacity-80">نسبة رضا العملاء</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">مجاني</div>
                            <div class="text-sm opacity-80">للطلبات +10 دينار</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">توصيل سريع</h3>
                <p class="text-gray-600">توصيل مجاني للطلبات أكثر من 10 دينار خلال ساعتين</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">جودة مضمونة</h3>
                <p class="text-gray-600">منتجات طازجة وعالية الجودة من أفضل الموردين</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">دفع آمن</h3>
                <p class="text-gray-600">طرق دفع متعددة وآمنة مع حماية كاملة للبيانات</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">دعم مستمر</h3>
                <p class="text-gray-600">فريق دعم متاح على مدار الساعة لمساعدتك</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">المنتجات المميزة</h2>
            <p class="text-xl text-gray-600">اكتشف أفضل المنتجات المختارة خصيصاً لك</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden group">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->getMainImage())
                        <img src="{{ asset('storage/' . $product->getMainImage()) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-gray-400">لا توجد صورة</div>
                        @endif
                    </div>
                    
                    @if($product->is_on_sale)
                    <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-medium">
                        خصم {{ $product->discount_percentage }}%
                    </span>
                    @endif

                    @auth
                    <button onclick="toggleWishlist({{ $product->id }})" class="absolute top-2 right-2 p-2 bg-white/80 hover:bg-white text-gray-600 hover:text-red-500 rounded-full transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                    @endauth
                </div>
                
                <div class="p-4">
                    <h3 class="font-medium text-sm leading-tight mb-2">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-600 mb-2">{{ $product->vendor->name }}</p>
                    
                    <div class="flex items-center gap-1 mb-2">
                        <svg class="h-3 w-3 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-xs font-medium">{{ number_format($product->rating, 1) }}</span>
                        <span class="text-xs text-gray-600">({{ $product->reviews_count }})</span>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <span class="font-bold text-primary">{{ $product->formatted_price }}</span>
                        @if($product->formatted_original_price)
                        <span class="text-xs text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('product.show', $product->id) }}" class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 py-2 px-3 rounded text-center text-sm transition-colors">
                            عرض
                        </a>
                        @auth
                        <button onclick="addToCart({{ $product->id }})" class="flex-1 bg-primary hover:bg-primary/90 text-white py-2 px-3 rounded text-sm transition-colors" {{ !$product->isInStock() ? 'disabled' : '' }}>
                            @if($product->isInStock())
                            إضافة
                            @else
                            غير متوفر
                            @endif
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="flex-1 bg-primary hover:bg-primary/90 text-white py-2 px-3 rounded text-center text-sm transition-colors">
                            إضافة
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('categories') }}" class="bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                عرض جميع المنتجات
            </a>
        </div>
    </div>
</section>
@endif

<!-- Fresh Products -->
@if($freshProducts->count() > 0)
<section class="py-12 bg-green-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">المنتجات الطازجة</h2>
            <p class="text-xl text-gray-600">منتجات طازجة يومياً من أفضل المزارع</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($freshProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->getMainImage())
                        <img src="{{ asset('storage/' . $product->getMainImage()) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-gray-400">لا توجد صورة</div>
                        @endif
                    </div>
                    
                    <span class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">
                        طازج
                    </span>
                </div>
                
                <div class="p-4">
                    <h3 class="font-medium mb-2">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $product->vendor->name }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-primary">{{ $product->formatted_price }}</span>
                        <a href="{{ route('product.show', $product->id) }}" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded text-sm transition-colors">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('fresh') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                عرض جميع المنتجات الطازجة
            </a>
        </div>
    </div>
</section>
@endif

<!-- Sale Products -->
@if($saleProducts->count() > 0)
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">العروض والخصومات</h2>
            <p class="text-xl text-gray-600">وفر أكثر مع عروضنا المميزة</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($saleProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->getMainImage())
                        <img src="{{ asset('storage/' . $product->getMainImage()) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-gray-400">لا توجد صورة</div>
                        @endif
                    </div>
                    
                    <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-medium">
                        خصم {{ $product->discount_percentage }}%
                    </span>
                </div>
                
                <div class="p-4">
                    <h3 class="font-medium mb-2">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $product->vendor->name }}</p>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <span class="font-bold text-red-600">{{ $product->formatted_price }}</span>
                        <span class="text-sm text-gray-500 line-through">{{ $product->formatted_original_price }}</span>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('product.show', $product->id) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition-colors">
                            اشتري الآن
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('offers') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                عرض جميع العروض
            </a>
        </div>
    </div>
</section>
@endif

<!-- Vendors -->
@if($vendors->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">موردينا المميزون</h2>
            <p class="text-xl text-gray-600">تعرف على أفضل الموردين لدينا</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vendors as $vendor)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6 text-center">
                <div class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    @if($vendor->logo)
                    <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="w-full h-full object-cover rounded-full">
                    @else
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    @endif
                </div>
                
                <h3 class="font-semibold mb-2">{{ $vendor->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $vendor->city }}، {{ $vendor->governorate }}</p>
                <p class="text-xs text-gray-500">{{ $vendor->products_count }} منتج</p>
                
                <div class="flex items-center justify-center gap-1 mt-2">
                    <svg class="h-4 w-4 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $vendor->formatted_rating }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // عرض رسالة نجاح
                alert(data.message);
                // تحديث عداد السلة
                updateCartCount();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إضافة المنتج');
        });
    }

    function toggleWishlist(productId) {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endpush
