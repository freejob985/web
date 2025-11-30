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
    echo ^<?php > server.php
    echo. >> server.php
    echo /** >> server.php
    echo  * Laravel - A PHP Framework For Web Artisans >> server.php
    echo  * >> server.php
    echo  * @package  Laravel >> server.php
    echo  * @author   Taylor Otwell ^<taylor@laravel.com^> >> server.php
    echo  */ >> server.php
    echo. >> server.php
    echo $uri = urldecode^( >> server.php
    echo     parse_url^($_SERVER['REQUEST_URI'], PHP_URL_PATH^) >> server.php
    echo ^); >> server.php
    echo. >> server.php
    echo // This file allows us to emulate Apache's "mod_rewrite" functionality from the >> server.php
    echo // built-in PHP web server. This provides a convenient way to test a Laravel >> server.php
    echo // application without having installed a "real" web server software here. >> server.php
    echo if ^($uri !== '/' ^&^& file_exists^(__DIR__.'/public'.$uri^)^) { >> server.php
    echo     return false; >> server.php
    echo } >> server.php
    echo. >> server.php
    echo require_once __DIR__.'/public/index.php'; >> server.php
    echo ✅ تم إنشاء ملف server.php
)

echo.
echo 2. التحقق من مجلد public...
if exist "public" (
    echo ✅ مجلد public موجود
) else (
    echo ❌ مجلد public غير موجود
    echo جاري إنشاء مجلد public...
    mkdir public
    echo ✅ تم إنشاء مجلد public
)

echo.
echo 3. التحقق من ملف public/index.php...
if exist "public\index.php" (
    echo ✅ ملف public/index.php موجود
) else (
    echo ❌ ملف public/index.php غير موجود
    echo جاري إنشاء ملف public/index.php...
    echo ^<?php > public\index.php
    echo. >> public\index.php
    echo use Illuminate\Contracts\Http\Kernel; >> public\index.php
    echo use Illuminate\Http\Request; >> public\index.php
    echo. >> public\index.php
    echo define^('LARAVEL_START', microtime^(true^)^); >> public\index.php
    echo. >> public\index.php
    echo /* >> public\index.php
    echo |-------------------------------------------------------------------------- >> public\index.php
    echo | Register The Auto Loader >> public\index.php
    echo |-------------------------------------------------------------------------- >> public\index.php
    echo | >> public\index.php
    echo | Composer provides a convenient, automatically generated class loader for >> public\index.php
    echo | this application. We just need to utilize it! We'll simply require it >> public\index.php
    echo | into the script here so we don't need to manually load our classes. >> public\index.php
    echo | >> public\index.php
    echo */ >> public\index.php
    echo. >> public\index.php
    echo require __DIR__.'/../vendor/autoload.php'; >> public\index.php
    echo. >> public\index.php
    echo $app = require_once __DIR__.'/../bootstrap/app.php'; >> public\index.php
    echo. >> public\index.php
    echo $kernel = $app->make^(Kernel::class^); >> public\index.php
    echo. >> public\index.php
    echo $response = $kernel->handle^( >> public\index.php
    echo     $request = Request::capture^(^) >> public\index.php
    echo ^)->send^(^); >> public\index.php
    echo. >> public\index.php
    echo $kernel->terminate^($request, $response^); >> public\index.php
    echo ✅ تم إنشاء ملف public/index.php
)

echo.
echo 4. إعادة تثبيت Composer dependencies...
composer install --no-interaction
if errorlevel 1 (
    echo ❌ فشل في تثبيت composer dependencies
    pause
    exit /b 1
) else (
    echo ✅ تم تثبيت composer dependencies بنجاح
)

echo.
echo 5. مسح cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo 6. اختبار السيرفر...
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
