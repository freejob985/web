<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شروط الخدمة - إنجب</title>
    <meta name="description" content="شروط الخدمة لموقع إنجب - تعرف على القوانين والشروط التي تحكم استخدامك لموقعنا الإلكتروني">
    <meta name="keywords" content="شروط الخدمة, إنجب, موقع إلكتروني, تسوق, شروط الاستخدام">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-bold text-blue-600">إنجب</a>
                    </div>
                    <nav class="hidden md:flex space-x-8 space-x-reverse">
                        <a href="/" class="text-gray-600 hover:text-blue-600">الرئيسية</a>
                        <a href="/about" class="text-gray-600 hover:text-blue-600">من نحن</a>
                        <a href="/contact" class="text-gray-600 hover:text-blue-600">اتصل بنا</a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <div id="terms-content">
                    <!-- المحتوى سيتم تحميله من API -->
                    <div class="text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-4 text-gray-600">جاري تحميل المحتوى...</p>
                    </div>
                </div>
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
                            <p class="text-gray-300"><i class="fas fa-envelope mr-2"></i> support@engeb.com</p>
                            <p class="text-gray-300"><i class="fas fa-phone mr-2"></i> +966 50 123 4567</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-300">&copy; 2024 إنجب. جميع الحقوق محفوظة.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // تحميل محتوى شروط الخدمة من API
        async function loadTermsContent() {
            try {
                const response = await fetch('/api/v1/terms');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('terms-content').innerHTML = data.data.content;
                } else {
                    document.getElementById('terms-content').innerHTML = `
                        <div class="text-center py-12">
                            <p class="text-red-600">حدث خطأ في تحميل المحتوى</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading terms content:', error);
                document.getElementById('terms-content').innerHTML = `
                    <div class="text-center py-12">
                        <p class="text-red-600">حدث خطأ في تحميل المحتوى</p>
                    </div>
                `;
            }
        }

        // تحميل المحتوى عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', loadTermsContent);
    </script>
</body>
</html>
