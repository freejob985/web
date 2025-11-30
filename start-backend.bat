@echo off
echo ========================================
echo    تشغيل سيرفر Laravel Backend
echo ========================================
echo.

cd /d "D:\server\htdocs\Domain_project\engeb"

echo جاري تشغيل سيرفر Laravel...
echo الرابط: http://localhost:8000
echo.

php artisan serve --host=0.0.0.0 --port=8000

pause