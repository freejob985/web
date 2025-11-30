@echo off
title Laravel Backend Server
color 0A

echo ========================================
echo    تشغيل سيرفر Laravel Backend
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo جاري تشغيل سيرفر Laravel...
echo الرابط: http://localhost:8000
echo API: http://localhost:8000/api/v1/
echo.

REM محاولة تشغيل السيرفر مع localhost أولاً
echo محاولة تشغيل السيرفر مع localhost...
php artisan serve --host=localhost --port=8000

REM إذا فشل، جرب 127.0.0.1
if errorlevel 1 (
    echo.
    echo محاولة تشغيل السيرفر مع 127.0.0.1...
    php artisan serve --host=127.0.0.1 --port=8000
)

REM إذا فشل أيضاً، جرب 0.0.0.0
if errorlevel 1 (
    echo.
    echo محاولة تشغيل السيرفر مع 0.0.0.0...
    php artisan serve --host=0.0.0.0 --port=8000
)

pause
