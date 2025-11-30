# تشغيل سيرفر Laravel Backend
Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل سيرفر Laravel Backend" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Set-Location "D:\server\htdocs\Domain_project\engeb"

Write-Host "جاري تشغيل سيرفر Laravel..." -ForegroundColor Yellow
Write-Host "الرابط: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""

php artisan serve --host=0.0.0.0 --port=8000
