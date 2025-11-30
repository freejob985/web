@echo off
title Project Launcher - Final Version
color 0E

echo ========================================
echo    تشغيل المشروع - النسخة النهائية
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo 1. إيقاف السيرفرات السابقة...
taskkill /f /im php.exe >nul 2>&1
taskkill /f /im node.exe >nul 2>&1
timeout /t 2 /nobreak >nul

echo 2. التحقق من ملف server.php...
if not exist "server.php" (
    echo ❌ ملف server.php غير موجود - جاري إنشاؤه...
    echo ^<?php > server.php
    echo $uri = urldecode^(parse_url^($_SERVER['REQUEST_URI'], PHP_URL_PATH^)^); >> server.php
    echo if ^($uri !== '/' ^&^& file_exists^(__DIR__.'/public'.$uri^)^) { >> server.php
    echo     return false; >> server.php
    echo } >> server.php
    echo require_once __DIR__.'/public/index.php'; >> server.php
    echo ✅ تم إنشاء ملف server.php
) else (
    echo ✅ ملف server.php موجود
)

echo.
echo 3. تشغيل Laravel Backend...
start "Laravel Backend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb && title Laravel Backend && color 0A && echo Laravel Backend Server && echo ======================================== && echo الرابط: http://localhost:8000 && echo API: http://localhost:8000/api/v1/ && echo Categories: http://localhost:8000/api/v1/categories/main && echo ======================================== && echo. && php artisan serve --host=localhost --port=8000"

echo انتظار تشغيل Backend...
timeout /t 5 /nobreak >nul

echo.
echo 4. تشغيل React Frontend...
start "React Frontend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb\Front && title React Frontend && color 0B && echo React Frontend Server && echo ======================================== && echo الرابط: http://localhost:5174 && echo ======================================== && echo. && npm run dev"

echo انتظار تشغيل Frontend...
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo    تم تشغيل المشروع بنجاح! 🎉
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

echo.
echo اضغط أي مفتاح للإغلاق...
pause >nul
