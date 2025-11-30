# تشغيل Frontend React/Vite
$Host.UI.RawUI.WindowTitle = "React Frontend Server"

Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل Frontend React/Vite" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Set-Location "D:\server\htdocs\Domain_project\engeb\Front"

Write-Host "جاري تشغيل سيرفر التطوير..." -ForegroundColor Yellow
Write-Host "الرابط: http://localhost:5174" -ForegroundColor Cyan
Write-Host ""

npm run dev
