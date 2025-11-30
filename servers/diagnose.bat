@echo off
title Project Diagnostics
color 0C

echo ========================================
echo    تشخيص مشاكل المشروع
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo 1. التحقق من PHP...
php --version
if errorlevel 1 (
    echo ❌ PHP غير مثبت أو غير موجود في PATH
) else (
    echo ✅ PHP مثبت بنجاح
)
echo.

echo 2. التحقق من Composer...
composer --version
if errorlevel 1 (
    echo ❌ Composer غير مثبت أو غير موجود في PATH
) else (
    echo ✅ Composer مثبت بنجاح
)
echo.

echo 3. التحقق من Node.js...
node --version
if errorlevel 1 (
    echo ❌ Node.js غير مثبت أو غير موجود في PATH
) else (
    echo ✅ Node.js مثبت بنجاح
)
echo.

echo 4. التحقق من npm...
npm --version
if errorlevel 1 (
    echo ❌ npm غير مثبت أو غير موجود في PATH
) else (
    echo ✅ npm مثبت بنجاح
)
echo.

echo 5. التحقق من ملفات Laravel...
if exist "artisan" (
    echo ✅ ملف artisan موجود
) else (
    echo ❌ ملف artisan غير موجود
)

if exist "composer.json" (
    echo ✅ ملف composer.json موجود
) else (
    echo ❌ ملف composer.json غير موجود
)

if exist ".env" (
    echo ✅ ملف .env موجود
) else (
    echo ❌ ملف .env غير موجود
)
echo.

echo 6. التحقق من ملفات Frontend...
cd /d "D:\server\htdocs\Domain_project\engeb\Front"
if exist "package.json" (
    echo ✅ ملف package.json موجود
) else (
    echo ❌ ملف package.json غير موجود
)

if exist "node_modules" (
    echo ✅ مجلد node_modules موجود
) else (
    echo ❌ مجلد node_modules غير موجود
)
echo.

echo 7. التحقق من المنافذ...
echo المنفذ 8000:
netstat -an | findstr :8000
if errorlevel 1 (
    echo ✅ المنفذ 8000 متاح
) else (
    echo ❌ المنفذ 8000 مستخدم
)

echo المنفذ 5174:
netstat -an | findstr :5174
if errorlevel 1 (
    echo ✅ المنفذ 5174 متاح
) else (
    echo ❌ المنفذ 5174 مستخدم
)
echo.

echo 8. اختبار الاتصال بالإنترنت...
ping -n 1 google.com >nul 2>&1
if errorlevel 1 (
    echo ❌ لا يوجد اتصال بالإنترنت
) else (
    echo ✅ الاتصال بالإنترنت يعمل
)
echo.

echo ========================================
echo    انتهى التشخيص
echo ========================================
echo.

pause
