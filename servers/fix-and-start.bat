@echo off
title Auto Fix and Start
color 0D

echo ========================================
echo    إصلاح تلقائي وتشغيل المشروع
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo 1. إيقاف السيرفرات السابقة...
taskkill /f /im php.exe >nul 2>&1
taskkill /f /im node.exe >nul 2>&1
timeout /t 2 /nobreak >nul

echo 2. تثبيت dependencies للـ Backend...
if exist "composer.json" (
    composer install --no-interaction
    if errorlevel 1 (
        echo ❌ فشل في تثبيت composer dependencies
        pause
        exit /b 1
    ) else (
        echo ✅ تم تثبيت composer dependencies بنجاح
    )
) else (
    echo ❌ ملف composer.json غير موجود
    pause
    exit /b 1
)

echo 3. تشغيل migrations...
php artisan migrate --force
if errorlevel 1 (
    echo ⚠️ تحذير: فشل في تشغيل migrations
) else (
    echo ✅ تم تشغيل migrations بنجاح
)

echo 4. مسح cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo 5. تثبيت dependencies للـ Frontend...
cd /d "D:\server\htdocs\Domain_project\engeb\Front"
if exist "package.json" (
    npm install
    if errorlevel 1 (
        echo ❌ فشل في تثبيت npm dependencies
        pause
        exit /b 1
    ) else (
        echo ✅ تم تثبيت npm dependencies بنجاح
    )
) else (
    echo ❌ ملف package.json غير موجود
    pause
    exit /b 1
)

echo 6. تشغيل المشروع...
cd /d "D:\server\htdocs\Domain_project\engeb\servers"
start "Project Launcher" cmd /k "start-all-smart.bat"

echo.
echo ========================================
echo    تم الإصلاح والتشغيل بنجاح!
echo ========================================
echo.

pause
