@extends('layouts.app')

@section('title', $product->name . ' - إيليت ون سوبر ماركت')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-primary">الرئيسية</a></li>
            <li class="mx-2">/</li>
            <li><a href="{{ route('product.category', $product->category) }}" class="hover:text-primary">{{ $product->category }}</a></li>
            <li class="mx-2">/</li>
            <li class="text-gray-400">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div>
            <div class="relative mb-4">
                <div class="w-full h-96 bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->getMainImage())
                    <img id="main-image" src="{{ asset('storage/' . $product->getMainImage()) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                
                @if($product->is_on_sale)
                <span class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-lg font-medium">
                    خصم {{ $product->discount_percentage }}%
                </span>
                @endif

                @if($product->is_fresh)
                <span class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-lg font-medium">
                    طازج
                </span>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if($product->images && count($product->images) > 1)
            <div class="grid grid-cols-4 gap-2">
                @foreach($product->images as $image)
                <div class="w-full h-20 bg-gray-200 rounded cursor-pointer overflow-hidden hover:opacity-75 transition-opacity" onclick="changeMainImage('{{ asset('storage/' . $image) }}')">
                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <div class="mb-4">
                <span class="text-sm text-gray-600">{{ $product->vendor->name }}</span>
                <h1 class="text-3xl font-bold mt-2">{{ $product->name }}</h1>
            </div>

            <!-- Rating -->
            <div class="flex items-center gap-2 mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="h-5 w-5 {{ $i <= floor($product->rating) ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    @endfor
                </div>
                <span class="text-sm font-medium">{{ number_format($product->rating, 1) }}</span>
                <span class="text-sm text-gray-600">({{ $product->reviews_count }} تقييم)</span>
                <span class="text-sm text-gray-600">• {{ $product->sales_count }} مبيعة</span>
            </div>

            <!-- Price -->
            <div class="flex items-center gap-4 mb-6">
                <div class="text-3xl font-bold text-primary">{{ $product->formatted_price }}</div>
                @if($product->formatted_original_price)
                <div class="text-xl text-gray-500 line-through">{{ $product->formatted_original_price }}</div>
                @endif
                @if($product->is_on_sale)
                <div class="text-green-600 font-medium">
                    وفر {{ number_format($product->original_price - $product->price, 3) }} دينار
                </div>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->isInStock())
                <div class="flex items-center gap-2 text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>متوفر في المخزون ({{ $product->stock }} {{ $product->unit }})</span>
                </div>
                @else
                <div class="flex items-center gap-2 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>غير متوفر حالياً</span>
                </div>
                @endif
            </div>

            <!-- Quantity Selector -->
            @if($product->isInStock())
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية</label>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button type="button" onclick="decreaseQuantity()" class="p-2 hover:bg-gray-100">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-0 focus:ring-0">
                        <button type="button" onclick="increaseQuantity()" class="p-2 hover:bg-gray-100">+</button>
                    </div>
                    <span class="text-sm text-gray-600">{{ $product->unit }}</span>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-4 mb-6">
                @if($product->isInStock())
                    @auth
                    <button onclick="addToCart()" class="flex-1 bg-primary hover:bg-primary/90 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 5H3m4 8v6a1 1 0 001 1h8a1 1 0 001-1v-6m-9 0h10"></path>
                        </svg>
                        إضافة للسلة - <span id="total-price">{{ $product->formatted_price }}</span>
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="flex-1 bg-primary hover:bg-primary/90 text-white py-3 px-6 rounded-lg font-medium text-center transition-colors">
                        سجل دخولك للشراء
                    </a>
                    @endauth
                @else
                <button disabled class="flex-1 bg-gray-300 text-gray-500 py-3 px-6 rounded-lg font-medium cursor-not-allowed">
                    غير متوفر
                </button>
                @endif

                @auth
                <button onclick="toggleWishlist()" class="p-3 border border-gray-300 hover:border-red-500 hover:text-red-500 rounded-lg transition-colors">
                    <svg id="wishlist-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                @endauth
            </div>

            <!-- Delivery Info -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span class="text-sm">توصيل مجاني للطلبات أكثر من 10 دينار</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">توصيل خلال 30-60 دقيقة</span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-4">
                @if($product->weight)
                <div class="flex justify-between">
                    <span class="text-gray-600">الوزن:</span>
                    <span>{{ $product->weight }} {{ $product->unit }}</span>
                </div>
                @endif

                @if($product->brand)
                <div class="flex justify-between">
                    <span class="text-gray-600">الماركة:</span>
                    <span>{{ $product->brand }}</span>
                </div>
                @endif

                <div class="flex justify-between">
                    <span class="text-gray-600">بلد المنشأ:</span>
                    <span>{{ $product->origin }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">رمز المنتج:</span>
                    <span>{{ $product->sku }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4">وصف المنتج</h2>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
        </div>
    </div>
    @endif

    <!-- Nutritional Info -->
    @if($product->nutritional_info)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4">المعلومات الغذائية</h2>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($product->nutritional_info as $key => $value)
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ $key }}:</span>
                    <span>{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">منتجات أخرى من {{ $product->vendor->name }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        @if($relatedProduct->getMainImage())
                        <img src="{{ asset('storage/' . $relatedProduct->getMainImage()) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-gray-400">لا توجد صورة</div>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-medium text-sm leading-tight mb-2">{{ $relatedProduct->name }}</h3>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <span class="font-bold text-primary">{{ $relatedProduct->formatted_price }}</span>
                        @if($relatedProduct->formatted_original_price)
                        <span class="text-xs text-gray-500 line-through">{{ $relatedProduct->formatted_original_price }}</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('product.show', $relatedProduct->id) }}" class="block w-full bg-primary hover:bg-primary/90 text-white py-2 px-3 rounded text-center text-sm transition-colors">
                        عرض المنتج
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">منتجات مشابهة</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($similarProducts as $similarProduct)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="relative">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        @if($similarProduct->getMainImage())
                        <img src="{{ asset('storage/' . $similarProduct->getMainImage()) }}" alt="{{ $similarProduct->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-gray-400">لا توجد صورة</div>
                        @endif
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-medium text-sm leading-tight mb-2">{{ $similarProduct->name }}</h3>
                    <p class="text-xs text-gray-600 mb-2">{{ $similarProduct->vendor->name }}</p>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <span class="font-bold text-primary">{{ $similarProduct->formatted_price }}</span>
                        @if($similarProduct->formatted_original_price)
                        <span class="text-xs text-gray-500 line-through">{{ $similarProduct->formatted_original_price }}</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('product.show', $similarProduct->id) }}" class="block w-full bg-primary hover:bg-primary/90 text-white py-2 px-3 rounded text-center text-sm transition-colors">
                        عرض المنتج
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    let quantity = 1;
    const maxQuantity = {{ $product->stock }};
    const unitPrice = {{ $product->price }};

    function increaseQuantity() {
        if (quantity < maxQuantity) {
            quantity++;
            updateQuantityDisplay();
        }
    }

    function decreaseQuantity() {
        if (quantity > 1) {
            quantity--;
            updateQuantityDisplay();
        }
    }

    function updateQuantityDisplay() {
        document.getElementById('quantity').value = quantity;
        document.getElementById('total-price').textContent = (unitPrice * quantity).toFixed(3) + ' دينار';
    }

    function changeMainImage(src) {
        document.getElementById('main-image').src = src;
    }

    function addToCart() {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
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

    function toggleWishlist() {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: {{ $product->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // تغيير أيقونة القلب
                const icon = document.getElementById('wishlist-icon');
                if (icon.getAttribute('fill') === 'none') {
                    icon.setAttribute('fill', 'currentColor');
                } else {
                    icon.setAttribute('fill', 'none');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // تحديث العرض عند تغيير الكمية من خلال الإدخال المباشر
    document.getElementById('quantity').addEventListener('change', function() {
        quantity = parseInt(this.value);
        if (quantity < 1) quantity = 1;
        if (quantity > maxQuantity) quantity = maxQuantity;
        updateQuantityDisplay();
    });
</script>
@endpush
