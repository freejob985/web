<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سياسة الاسترداد - إنجب</title>
    <meta name="description" content="سياسة الاسترداد لموقع إنجب - تعرف على شروط وأحكام إرجاع واسترداد المنتجات">
    <meta name="keywords" content="سياسة الاسترداد, إنجب, إرجاع المنتجات, استرداد الأموال, ضمان الجودة">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        إنجب
                    </a>
                </div>
                <nav class="hidden md:flex space-x-8 space-x-reverse">
                    <a href="/" class="text-gray-600 hover:text-blue-600 transition-colors">الرئيسية</a>
                    <a href="/terms" class="text-gray-600 hover:text-blue-600 transition-colors">شروط الخدمة</a>
                    <a href="/privacy" class="text-gray-600 hover:text-blue-600 transition-colors">سياسة الخصوصية</a>
                    <a href="/refund" class="text-blue-600 font-semibold">سياسة الاسترداد</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div id="refund-content" class="bg-white rounded-lg shadow-sm p-8">
            <!-- Loading State -->
            <div id="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">جاري تحميل المحتوى...</p>
            </div>

            <!-- Error State -->
            <div id="error" class="hidden text-center py-12">
                <div class="text-red-500 text-6xl mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">خطأ في تحميل المحتوى</h2>
                <p class="text-gray-600 mb-6">عذراً، حدث خطأ أثناء تحميل صفحة سياسة الاسترداد.</p>
                <button onclick="loadRefundContent()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>
                    إعادة المحاولة
                </button>
            </div>

            <!-- Content will be loaded here -->
            <div id="content" class="hidden"></div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">إنجب</h3>
                    <p class="text-gray-300">منصة تسوق إلكترونية متكاملة تقدم أفضل المنتجات والخدمات لعملائنا الكرام.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">روابط مهمة</h3>
                    <ul class="space-y-2">
                        <li><a href="/terms" class="text-gray-300 hover:text-white transition-colors">شروط الخدمة</a></li>
                        <li><a href="/privacy" class="text-gray-300 hover:text-white transition-colors">سياسة الخصوصية</a></li>
                        <li><a href="/refund" class="text-gray-300 hover:text-white transition-colors">سياسة الاسترداد</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">تواصل معنا</h3>
                    <div class="space-y-2">
                        <p class="text-gray-300"><i class="fas fa-envelope mr-2"></i> info@engeb.com</p>
                        <p class="text-gray-300"><i class="fas fa-phone mr-2"></i> +966 50 123 4567</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; 2024 إنجب. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Load refund content from API
        async function loadRefundContent() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const content = document.getElementById('content');

            // Show loading state
            loading.classList.remove('hidden');
            error.classList.add('hidden');
            content.classList.add('hidden');

            try {
                const response = await fetch('/api/v1/refund', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.data) {
                    // Update page title and meta
                    document.title = data.data.title + ' - إنجب';
                    
                    // Update content
                    content.innerHTML = data.data.content;
                    
                    // Show content
                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                } else {
                    throw new Error(data.message || 'فشل في تحميل المحتوى');
                }
            } catch (err) {
                console.error('Error loading refund content:', err);
                
                // Show error state
                loading.classList.add('hidden');
                error.classList.remove('hidden');
            }
        }

        // Load content when page loads
        document.addEventListener('DOMContentLoaded', loadRefundContent);
    </script>
</body>
</html>
