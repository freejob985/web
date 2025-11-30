<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم المورد - إيليت ون سوبر ماركت')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-cairo bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3">
                        <img 
                            src="https://cdn.builder.io/api/v1/image/assets%2Ffed240d3cc2b421da3511c77b247579c%2F117059de60a149a98d347c3289677bd1?format=webp&width=800" 
                            alt="Elite1 Logo" 
                            class="h-10 w-auto"
                        />
                    </a>
                    <div>
                        <h1 class="text-xl font-bold">{{ auth('vendor')->user()->name }}</h1>
                        <p class="text-sm text-gray-600">لوحة تحكم المورد</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5M12 22l-3-3h6l-3 3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                            {{ auth('vendor')->user()->pending_orders_count }}
                        </span>
                    </button>
                    
                    <!-- Settings -->
                    <a href="#" class="p-2 text-gray-600 hover:text-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('vendor.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded text-sm transition-colors">
                            <svg class="h-4 w-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m10 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h8a3 3 0 013 3v1"></path>
                            </svg>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar & Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-sm min-h-screen">
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('vendor.dashboard') ? 'bg-primary text-white hover:bg-primary' : '' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v5a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                            </svg>
                            لوحة التحكم
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('vendor.products') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('vendor.products*') ? 'bg-primary text-white hover:bg-primary' : '' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            المنتجات
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('vendor.orders') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('vendor.orders*') ? 'bg-primary text-white hover:bg-primary' : '' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            الطلبات
                            @if(auth('vendor')->user()->pending_orders_count > 0)
                            <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                {{ auth('vendor')->user()->pending_orders_count }}
                            </span>
                            @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            التقارير
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            المالية
                        </a>
                    </li>
                    
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            الإعدادات
                        </a>
                    </li>
                </ul>
                
                <!-- Quick Stats -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-700 mb-3">إحصائيات سريعة</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">المنتجات النشطة</span>
                            <span class="font-medium">{{ auth('vendor')->user()->active_products_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">الطلبات الجديدة</span>
                            <span class="font-medium text-orange-600">{{ auth('vendor')->user()->pending_orders_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">التقييم</span>
                            <div class="flex items-center gap-1">
                                <svg class="h-3 w-3 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-medium">{{ auth('vendor')->user()->formatted_rating }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Store -->
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="block w-full text-center text-primary hover:bg-primary hover:text-white border border-primary py-2 px-4 rounded-lg text-sm transition-colors">
                        <svg class="h-4 w-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        العودة للمتجر
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success mb-6">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error mb-6">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
