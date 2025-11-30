# تشغيل كامل للمشروع
$Host.UI.RawUI.WindowTitle = "Project Launcher"

Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل كامل للمشروع" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "جاري تشغيل Backend و Frontend معاً..." -ForegroundColor Yellow
Write-Host ""

# تشغيل Backend في نافذة منفصلة
$backendScript = @"
cd 'D:\server\htdocs\Domain_project\engeb'
`$Host.UI.RawUI.WindowTitle = 'Laravel Backend'
Write-Host 'Laravel Backend Server' -ForegroundColor Green
Write-Host '========================================' -ForegroundColor Green
Write-Host 'الرابط: http://localhost:8000' -ForegroundColor Cyan
Write-Host 'API: http://localhost:8000/api/v1/' -ForegroundColor Cyan
Write-Host '========================================' -ForegroundColor Green
Write-Host ''
php artisan serve --host=127.0.0.1 --port=8000
"@

Start-Process powershell -ArgumentList "-NoExit", "-Command", $backendScript

# انتظار 5 ثوان
Start-Sleep -Seconds 5

# تشغيل Frontend في نافذة منفصلة
$frontendScript = @"
cd 'D:\server\htdocs\Domain_project\engeb\Front'
`$Host.UI.RawUI.WindowTitle = 'React Frontend'
Write-Host 'React Frontend Server' -ForegroundColor Green
Write-Host '========================================' -ForegroundColor Green
Write-Host 'الرابط: http://localhost:5174' -ForegroundColor Cyan
Write-Host '========================================' -ForegroundColor Green
Write-Host ''
npm run dev
"@

Start-Process powershell -ArgumentList "-NoExit", "-Command", $frontendScript

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "    تم تشغيل المشروع بنجاح!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "Backend:  http://localhost:8000" -ForegroundColor Cyan
Write-Host "Frontend: http://localhost:5174" -ForegroundColor Cyan
Write-Host "API:      http://localhost:8000/api/v1/" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "تم فتح نافذتين منفصلتين للسيرفرات" -ForegroundColor Yellow
Write-Host "لإيقاف السيرفرات، أغلق النوافذ أو اضغط Ctrl+C" -ForegroundColor Yellow
Write-Host ""

Read-Host "اضغط Enter للإغلاق"
