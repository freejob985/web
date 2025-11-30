# تشغيل كامل للمشروع
Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل كامل للمشروع" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "جاري تشغيل Backend و Frontend معاً..." -ForegroundColor Yellow
Write-Host ""

# تشغيل Backend في نافذة منفصلة
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'D:\server\htdocs\Domain_project\engeb'; Write-Host 'Laravel Backend' -ForegroundColor Green; php artisan serve --host=0.0.0.0 --port=8000"

# انتظار 3 ثوان
Start-Sleep -Seconds 3

# تشغيل Frontend في نافذة منفصلة
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'D:\server\htdocs\Domain_project\engeb\Front'; Write-Host 'React Frontend' -ForegroundColor Green; npm run dev"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "    تم تشغيل المشروع بنجاح!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "Backend:  http://localhost:8000" -ForegroundColor Cyan
Write-Host "Frontend: http://localhost:5174" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Read-Host "اضغط Enter للإغلاق"
