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

php artisan serve --host=127.0.0.1 --port=8000

pause
