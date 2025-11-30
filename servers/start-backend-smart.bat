@echo off
title Laravel Backend Server - Smart Launcher
color 0A

echo ========================================
echo    تشغيل سيرفر Laravel Backend
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

REM التحقق من وجود PHP
php --version >nul 2>&1
if errorlevel 1 (
    echo خطأ: PHP غير مثبت أو غير موجود في PATH
    echo يرجى تثبيت PHP وإضافته إلى PATH
    pause
    exit /b 1
)

REM التحقق من وجود Laravel
if not exist "artisan" (
    echo خطأ: ملف artisan غير موجود
    echo تأكد من أنك في مجلد Laravel الصحيح
    pause
    exit /b 1
)

echo جاري تشغيل سيرفر Laravel...
echo.

REM إيقاف أي سيرفر يعمل على المنفذ 8000
echo إيقاف السيرفرات السابقة...
netstat -ano | findstr :8000 >nul 2>&1
if not errorlevel 1 (
    for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000') do (
        taskkill /f /pid %%a >nul 2>&1
    )
)

REM انتظار قليل
timeout /t 2 /nobreak >nul

REM تشغيل السيرفر مع localhost (الأفضل)
echo تشغيل السيرفر مع localhost...
start /b php artisan serve --host=localhost --port=8000

REM انتظار قليل للتأكد من التشغيل
timeout /t 3 /nobreak >nul

REM اختبار الاتصال
echo اختبار الاتصال...
curl -s http://localhost:8000 >nul 2>&1
if not errorlevel 1 (
    echo.
    echo ========================================
    echo    تم تشغيل السيرفر بنجاح!
    echo ========================================
    echo الرابط: http://localhost:8000
    echo API: http://localhost:8000/api/v1/
    echo Categories: http://localhost:8000/api/v1/categories/main
    echo ========================================
    echo.
    echo السيرفر يعمل في الخلفية
    echo لإيقافه، استخدم Ctrl+C أو أغلق هذه النافذة
    echo.
) else (
    echo.
    echo محاولة تشغيل السيرفر مع 127.0.0.1...
    taskkill /f /im php.exe >nul 2>&1
    timeout /t 2 /nobreak >nul
    start /b php artisan serve --host=127.0.0.1 --port=8000
    timeout /t 3 /nobreak >nul
    
    curl -s http://127.0.0.1:8000 >nul 2>&1
    if not errorlevel 1 (
        echo.
        echo ========================================
        echo    تم تشغيل السيرفر بنجاح!
        echo ========================================
        echo الرابط: http://127.0.0.1:8000
        echo API: http://127.0.0.1:8000/api/v1/
        echo Categories: http://127.0.0.1:8000/api/v1/categories/main
        echo ========================================
        echo.
    ) else (
        echo.
        echo خطأ: فشل في تشغيل السيرفر
        echo تأكد من:
        echo 1. تثبيت PHP
        echo 2. تثبيت Composer
        echo 3. تشغيل composer install
        echo 4. تشغيل php artisan migrate
        echo.
    )
)

pause
