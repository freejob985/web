@echo off
echo ========================================
echo    تشغيل كامل للمشروع
echo ========================================
echo.

echo جاري تشغيل Backend و Frontend معاً...
echo.

start "Laravel Backend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb && php artisan serve --host=0.0.0.0 --port=8000"

timeout /t 3 /nobreak >nul

start "React Frontend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb\Front && npm run dev"

echo.
echo ========================================
echo    تم تشغيل المشروع بنجاح!
echo ========================================
echo Backend:  http://localhost:8000
echo Frontend: http://localhost:5174
echo ========================================
echo.

pause