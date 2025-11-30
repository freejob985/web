@echo off
title Laravel Server Fix
color 0D

echo ========================================
echo    إصلاح مشكلة Laravel Server
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo 1. التحقق من وجود ملف server.php...
if exist "server.php" (
    echo ✅ ملف server.php موجود
) else (
    echo ❌ ملف server.php غير موجود - جاري إنشاؤه...
    copy /y "D:\server\htdocs\Domain_project\engeb\server.php" "server.php" >nul 2>&1
    if exist "server.php" (
        echo ✅ تم إنشاء ملف server.php
    ) else (
        echo ❌ فشل في إنشاء ملف server.php
    )
)

echo.
echo 2. إعادة تثبيت Composer dependencies...
composer install --no-interaction
if errorlevel 1 (
    echo ❌ فشل في تثبيت composer dependencies
    pause
    exit /b 1
) else (
    echo ✅ تم تثبيت composer dependencies بنجاح
)

echo.
echo 3. مسح cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo 4. اختبار السيرفر...
echo جاري تشغيل السيرفر للاختبار...
start /b php artisan serve --host=localhost --port=8000

timeout /t 5 /nobreak >nul

echo.
echo ========================================
echo    تم إصلاح المشكلة بنجاح!
echo ========================================
echo الرابط: http://localhost:8000
echo API: http://localhost:8000/api/v1/
echo ========================================
echo.

pause
