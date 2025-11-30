# 🚀 ملفات تشغيل المشروع

هذا المجلد يحتوي على ملفات لتشغيل سيرفرات المشروع بسهولة مع حلول شاملة للمشاكل الشائعة.

## 📁 الملفات المتاحة

### ملفات Batch (.bat) - النسخة العادية
- `start-backend.bat` - تشغيل سيرفر Laravel فقط
- `start-frontend.bat` - تشغيل سيرفر React/Vite فقط  
- `start-all.bat` - تشغيل كلا السيرفرين معاً

### ملفات Batch (.bat) - النسخة الذكية (مُوصى بها)
- `start-backend-smart.bat` - تشغيل سيرفر Laravel مع حلول ذكية للمشاكل
- `start-all-smart.bat` - تشغيل كلا السيرفرين مع حلول ذكية
- `start-backend-fixed.bat` - تشغيل Laravel مع محاولات متعددة للاتصال
- `start-project-final.bat` - **النسخة النهائية المُوصى بها** - تشمل حل مشكلة server.php

### ملفات الإصلاح
- `fix-laravel-server.bat` - إصلاح مشكلة Laravel server.php
- `fix-laravel-server-simple.bat` - إصلاح بسيط لمشكلة server.php

### ملفات PowerShell (.ps1)
- `start-backend.ps1` - تشغيل سيرفر Laravel فقط
- `start-frontend.ps1` - تشغيل سيرفر React/Vite فقط
- `start-all.ps1` - تشغيل كلا السيرفرين معاً
- `start-backend-smart.ps1` - تشغيل Laravel مع حلول ذكية

### ملفات التشخيص والإصلاح
- `diagnose.bat` - تشخيص مشاكل المشروع
- `fix-and-start.bat` - إصلاح تلقائي وتشغيل المشروع

## 🎯 كيفية الاستخدام

### الطريقة الأولى: تشغيل سريع
1. انقر نقراً مزدوجاً على `start-all.bat`
2. ستفتح نافذتان منفصلتان للسيرفرين
3. انتظر حتى تظهر رسالة "تم تشغيل المشروع بنجاح!"

### الطريقة الثانية: تشغيل منفصل
1. انقر نقراً مزدوجاً على `start-backend.bat` لتشغيل Laravel
2. انقر نقراً مزدوجاً على `start-frontend.bat` لتشغيل React

### الطريقة الثالثة: PowerShell
1. افتح PowerShell كمدير
2. انتقل إلى مجلد servers
3. شغل: `.\start-all.ps1`

## 🌐 الروابط

بعد التشغيل، يمكنك الوصول إلى:

- **Frontend**: http://localhost:5174
- **Backend**: http://localhost:8000
- **API**: http://localhost:8000/api/v1/
- **Categories API**: http://localhost:8000/api/v1/categories/main

## 🔧 استكشاف الأخطاء

### المشكلة: السيرفر يعمل على 0.0.0.0:8000 ولكن لا يمكن الوصول إليه من localhost:8000

**الحل:**
1. استخدم `start-backend-smart.bat` - يحاول localhost أولاً ثم 127.0.0.1
2. أو استخدم `start-backend-fixed.bat` - يجرب عدة خيارات
3. أو شغل `fix-and-start.bat` للإصلاح التلقائي

### المشكلة: "Failed opening required 'server.php'" - مشكلة Laravel 11

**الحل:**
1. استخدم `start-project-final.bat` - يحل المشكلة تلقائياً
2. أو شغل `fix-laravel-server-simple.bat` لإصلاح المشكلة فقط
3. أو أنشئ ملف server.php يدوياً في مجلد المشروع

**ملف server.php المطلوب:**
```php
<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require_once __DIR__.'/public/index.php';
```

### إذا لم يعمل Backend:
1. شغل `diagnose.bat` لتشخيص المشكلة
2. تأكد من تثبيت PHP و Composer
3. شغل `fix-and-start.bat` للإصلاح التلقائي
4. أو شغل يدوياً:
   - `composer install`
   - `php artisan migrate`
   - `php artisan serve --host=localhost --port=8000`

### إذا لم يعمل Frontend:
1. تأكد من تثبيت Node.js
2. شغل `npm install` في مجلد Front
3. تأكد من وجود ملف package.json

### إذا لم تعمل API:
1. تأكد من أن Laravel يعمل على http://localhost:8000
2. تحقق من ملف .env
3. تأكد من إعدادات CORS
4. جرب http://127.0.0.1:8000 بدلاً من localhost

### مشاكل شائعة وحلولها:

**مشكلة: "PHP not found"**
- تأكد من تثبيت PHP
- أضف PHP إلى PATH
- أعد تشغيل Command Prompt

**مشكلة: "Port already in use"**
- شغل `diagnose.bat` لرؤية المنافذ المستخدمة
- أو استخدم `fix-and-start.bat` لإيقاف السيرفرات السابقة

**مشكلة: "Laravel not found"**
- تأكد من وجود ملف artisan
- تأكد من أنك في المجلد الصحيح

**مشكلة: "CORS error"**
- تحقق من إعدادات CORS في Laravel
- تأكد من أن Frontend يرسل الطلبات للرابط الصحيح

## ⚠️ ملاحظات مهمة

- تأكد من إغلاق السيرفرات السابقة قبل تشغيل جديدة
- استخدم Ctrl+C لإيقاف السيرفرات
- تأكد من أن المنافذ 8000 و 5174 متاحة

## 🆘 الدعم

إذا واجهت مشاكل:
1. تحقق من رسائل الخطأ في النوافذ المفتوحة
2. تأكد من تثبيت جميع المتطلبات
3. تأكد من صحة المسارات في الملفات
