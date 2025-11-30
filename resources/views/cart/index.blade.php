@extends('layouts.app')

@section('title', 'سلة التسوق - إيليت ون سوبر ماركت')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">سلة التسوق</h1>
        <p class="text-gray-600">راجع منتجاتك واكمل عملية الشراء</p>
    </div>

    @if($cartItems->count() > 0)
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white p-6 rounded-lg shadow-sm border" data-item-id="{{ $item->id }}">
                <div class="flex gap-4">
                    <!-- Product Image -->
                    <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product->getMainImage())
                        <img src="{{ asset('storage/' . $item->product->getMainImage()) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-lg">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->product->vendor->name }}</p>
                                @if($item->product->is_fresh)
                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mt-1">طازج</span>
                                @endif
                            </div>
                            
                            <!-- Remove Button -->
                            <button onclick="removeFromCart({{ $item->id }})" class="text-red-600 hover:text-red-800 p-1">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Quantity and Price -->
                        <div class="flex justify-between items-center">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="p-2 hover:bg-gray-100 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="px-4 py-2 font-medium">{{ $item->quantity }}</span>
                                <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="p-2 hover:bg-gray-100 {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                <div class="font-bold text-lg">{{ number_format($item->product->price * $item->quantity, 3) }} دينار</div>
                                @if($item->product->original_price && $item->product->original_price > $item->product->price)
                                <div class="text-sm text-gray-500 line-through">
                                    {{ number_format($item->product->original_price * $item->quantity, 3) }} دينار
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($item->notes)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">ملاحظة: {{ $item->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Continue Shopping -->
            <div class="text-center py-4">
                <a href="{{ route('categories') }}" class="text-primary hover:underline font-medium">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                    </svg>
                    متابعة التسوق
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white p-6 rounded-lg shadow-sm border h-fit">
            <h2 class="text-xl font-bold mb-4">ملخص الطلب</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>المجموع الفرعي</span>
                    <span>{{ number_format($subtotal, 3) }} دينار</span>
                </div>
                
                @if($savings > 0)
                <div class="flex justify-between text-green-600">
                    <span>التوفير</span>
                    <span>-{{ number_format($savings, 3) }} دينار</span>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span>رسوم التوصيل</span>
                    <span class="{{ $deliveryFee === 0 ? 'text-green-600' : '' }}">
                        {{ $deliveryFee === 0 ? 'مجاني' : number_format($deliveryFee, 3) . ' دينار' }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span>ضريبة القيمة المضافة (15%)</span>
                    <span>{{ number_format($tax, 3) }} دينار</span>
                </div>
                
                <hr class="my-3">
                
                <div class="flex justify-between text-lg font-bold">
                    <span>المجموع الكلي</span>
                    <span>{{ number_format($total, 3) }} دينار</span>
                </div>
            </div>

            @if($subtotal < 10)
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <svg class="h-4 w-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    أضف {{ number_format(10 - $subtotal, 3) }} دينار للحصول على توصيل مجاني
                </p>
            </div>
            @endif

            <div class="mt-6 space-y-3">
                <a href="{{ route('checkout.index') }}" class="block w-full bg-primary hover:bg-primary/90 text-white py-3 px-4 rounded-lg font-medium text-center transition-colors">
                    إكمال الطلب
                </a>
                
                <button onclick="clearCart()" class="block w-full border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-lg font-medium text-center transition-colors">
                    مسح السلة
                </button>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Cart -->
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 5H3m4 8v6a1 1 0 001 1h8a1 1 0 001-1v-6m-9 0h10"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">سلة التسوق فارغة</h2>
        <p class="text-gray-600 mb-8">اكتشف منتجاتنا المميزة وأضف ما يعجبك إلى السلة</p>
        
        <div class="space-y-4">
            <a href="{{ route('categories') }}" class="inline-block bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                تصفح المنتجات
            </a>
            
            <div class="flex justify-center gap-4 text-sm">
                <a href="{{ route('offers') }}" class="text-primary hover:underline">العروض الخاصة</a>
                <a href="{{ route('fresh') }}" class="text-primary hover:underline">المنتجات الطازجة</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) return;
        
        fetch(`/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // إعادة تحميل الصفحة لتحديث الأسعار
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث الكمية');
        });
    }

    function removeFromCart(itemId) {
        if (!confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟')) {
            return;
        }

        fetch(`/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // إزالة العنصر من الصفحة
                document.querySelector(`[data-item-id="${itemId}"]`).remove();
                
                // تحديث عداد السلة
                updateCartCount();
                
                // إعادة تحميل الصفحة إذا كانت السلة فارغة الآن
                if (document.querySelectorAll('[data-item-id]').length === 0) {
                    location.reload();
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف المنتج');
        });
    }

    function clearCart() {
        if (!confirm('هل أنت متأكد من مسح جميع المنتجات من السلة؟')) {
            return;
        }

        fetch('/cart', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء مسح السلة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء مسح السلة');
        });
    }
</script>
@endpush
