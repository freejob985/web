@echo off
title Project Launcher - Smart Version
color 0E

echo ========================================
echo    تشغيل كامل للمشروع - النسخة الذكية
echo ========================================
echo.

REM التحقق من وجود PHP
php --version >nul 2>&1
if errorlevel 1 (
    echo خطأ: PHP غير مثبت أو غير موجود في PATH
    pause
    exit /b 1
)

REM التحقق من وجود Node.js
node --version >nul 2>&1
if errorlevel 1 (
    echo خطأ: Node.js غير مثبت أو غير موجود في PATH
    pause
    exit /b 1
)

echo جاري تشغيل Backend و Frontend معاً...
echo.

REM إيقاف أي سيرفرات سابقة
echo إيقاف السيرفرات السابقة...
taskkill /f /im php.exe >nul 2>&1
taskkill /f /im node.exe >nul 2>&1
timeout /t 2 /nobreak >nul

REM تشغيل Backend
echo تشغيل Laravel Backend...
start "Laravel Backend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb && title Laravel Backend && color 0A && echo Laravel Backend Server && echo ======================================== && echo جاري تشغيل السيرفر... && php artisan serve --host=localhost --port=8000"

REM انتظار حتى يبدأ Backend
echo انتظار تشغيل Backend...
timeout /t 5 /nobreak >nul

REM اختبار Backend
echo اختبار Backend...
curl -s http://localhost:8000 >nul 2>&1
if errorlevel 1 (
    echo تحذير: Backend قد لا يعمل بشكل صحيح
    echo جاري تشغيل Frontend على أي حال...
) else (
    echo Backend يعمل بنجاح!
)

REM تشغيل Frontend
echo تشغيل React Frontend...
start "React Frontend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb\Front && title React Frontend && color 0B && echo React Frontend Server && echo ======================================== && echo جاري تشغيل السيرفر... && npm run dev"

REM انتظار قليل
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo    تم تشغيل المشروع بنجاح!
echo ========================================
echo Backend:  http://localhost:8000
echo Frontend: http://localhost:5174
echo API:      http://localhost:8000/api/v1/
echo Categories: http://localhost:8000/api/v1/categories/main
echo ========================================
echo.
echo تم فتح نافذتين منفصلتين للسيرفرات
echo لإيقاف السيرفرات، أغلق النوافذ أو اضغط Ctrl+C
echo.

REM فتح المتصفح تلقائياً
echo فتح المتصفح...
start http://localhost:8000
timeout /t 2 /nobreak >nul
start http://localhost:5174

pause
