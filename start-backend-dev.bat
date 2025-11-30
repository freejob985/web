@echo off
echo Starting Laravel Backend Development Server...
echo.
echo Backend will be available at: http://localhost:8000
echo Admin Panel will be available at: http://localhost:8000/admin
echo API will be available at: http://localhost:8000/api/v1
echo.
php artisan serve --host=localhost --port=8000
pause
