@echo off
title Project Launcher
color 0E

echo ========================================
echo    تشغيل كامل للمشروع
echo ========================================
echo.

echo جاري تشغيل Backend و Frontend معاً...
echo.

start "Laravel Backend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb && title Laravel Backend && color 0A && echo Laravel Backend Server && echo ======================================== && echo الرابط: http://localhost:8000 && echo API: http://localhost:8000/api/v1/ && echo ======================================== && php artisan serve --host=127.0.0.1 --port=8000"

timeout /t 5 /nobreak >nul

start "React Frontend" cmd /k "cd /d D:\server\htdocs\Domain_project\engeb\Front && title React Frontend && color 0B && echo React Frontend Server && echo ======================================== && echo الرابط: http://localhost:5174 && echo ======================================== && npm run dev"

echo.
echo ========================================
echo    تم تشغيل المشروع بنجاح!
echo ========================================
echo Backend:  http://localhost:8000
echo Frontend: http://localhost:5174
echo API:      http://localhost:8000/api/v1/
echo ========================================
echo.
echo تم فتح نافذتين منفصلتين للسيرفرات
echo لإيقاف السيرفرات، أغلق النوافذ أو اضغط Ctrl+C
echo.

pause
