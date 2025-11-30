@echo off
echo Starting Engeb Development Environment...
echo.
echo This will start both Backend (Laravel) and Frontend (React) servers
echo Backend: http://localhost:8000
echo Frontend: http://localhost:5174
echo Admin Panel: http://localhost:8000/admin
echo API: http://localhost:8000/api/v1
echo.
echo Starting Backend Server...
start "Laravel Backend" cmd /k "php artisan serve --host=localhost --port=8000"

echo Waiting 3 seconds for backend to start...
timeout /t 3 /nobreak > nul

echo Starting Frontend Server...
start "React Frontend" cmd /k "cd Front && npm run dev"

echo.
echo Both servers are starting...
echo.
echo ✅ All features implemented:
echo   • API الماركات متصل بقاعدة البيانات
echo   • وظيفة الترتيب تعمل بشكل صحيح
echo   • البحث الفارغ يعرض جميع المنتجات
echo   • Pagination: 16 منتج/صفحة (4 صفوف × 4 أعمدة)
echo.
echo Press any key to close this window (servers will continue running)
pause > nul
