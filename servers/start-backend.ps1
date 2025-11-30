# تشغيل سيرفر Laravel Backend
$Host.UI.RawUI.WindowTitle = "Laravel Backend Server"

Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل سيرفر Laravel Backend" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Set-Location "D:\server\htdocs\Domain_project\engeb"

Write-Host "جاري تشغيل سيرفر Laravel..." -ForegroundColor Yellow
Write-Host "الرابط: http://localhost:8000" -ForegroundColor Cyan
Write-Host "API: http://localhost:8000/api/v1/" -ForegroundColor Cyan
Write-Host ""

php artisan serve --host=127.0.0.1 --port=8000
