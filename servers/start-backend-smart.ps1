# تشغيل سيرفر Laravel Backend - النسخة الذكية
$Host.UI.RawUI.WindowTitle = "Laravel Backend Server - Smart Launcher"

Write-Host "========================================" -ForegroundColor Green
Write-Host "    تشغيل سيرفر Laravel Backend" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# التحقق من وجود PHP
try {
    $phpVersion = php --version 2>$null
    if (-not $phpVersion) {
        throw "PHP not found"
    }
    Write-Host "PHP مثبت: $($phpVersion[0])" -ForegroundColor Green
} catch {
    Write-Host "خطأ: PHP غير مثبت أو غير موجود في PATH" -ForegroundColor Red
    Read-Host "اضغط Enter للإغلاق"
    exit 1
}

# التحقق من وجود Laravel
Set-Location "D:\server\htdocs\Domain_project\engeb"
if (-not (Test-Path "artisan")) {
    Write-Host "خطأ: ملف artisan غير موجود" -ForegroundColor Red
    Write-Host "تأكد من أنك في مجلد Laravel الصحيح" -ForegroundColor Red
    Read-Host "اضغط Enter للإغلاق"
    exit 1
}

Write-Host "جاري تشغيل سيرفر Laravel..." -ForegroundColor Yellow
Write-Host ""

# إيقاف أي سيرفر يعمل على المنفذ 8000
Write-Host "إيقاف السيرفرات السابقة..." -ForegroundColor Yellow
$processes = Get-Process -Name "php" -ErrorAction SilentlyContinue
if ($processes) {
    $processes | Stop-Process -Force
    Start-Sleep -Seconds 2
}

# دالة لاختبار الاتصال
function Test-ServerConnection {
    param([string]$url)
    try {
        $response = Invoke-WebRequest -Uri $url -TimeoutSec 5 -UseBasicParsing
        return $response.StatusCode -eq 200
    } catch {
        return $false
    }
}

# محاولة تشغيل السيرفر مع localhost
Write-Host "تشغيل السيرفر مع localhost..." -ForegroundColor Yellow
$job = Start-Job -ScriptBlock {
    Set-Location "D:\server\htdocs\Domain_project\engeb"
    php artisan serve --host=localhost --port=8000
}

# انتظار قليل
Start-Sleep -Seconds 5

# اختبار الاتصال
if (Test-ServerConnection "http://localhost:8000") {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "    تم تشغيل السيرفر بنجاح!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "الرابط: http://localhost:8000" -ForegroundColor Cyan
    Write-Host "API: http://localhost:8000/api/v1/" -ForegroundColor Cyan
    Write-Host "Categories: http://localhost:8000/api/v1/categories/main" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "السيرفر يعمل في الخلفية" -ForegroundColor Yellow
    Write-Host "لإيقافه، استخدم Ctrl+C أو أغلق هذه النافذة" -ForegroundColor Yellow
    Write-Host ""
    
    # فتح المتصفح
    Write-Host "فتح المتصفح..." -ForegroundColor Yellow
    Start-Process "http://localhost:8000"
    
    # انتظار المستخدم
    Read-Host "اضغط Enter لإيقاف السيرفر"
    Stop-Job $job
    Remove-Job $job
} else {
    Write-Host "محاولة تشغيل السيرفر مع 127.0.0.1..." -ForegroundColor Yellow
    Stop-Job $job
    Remove-Job $job
    
    $job = Start-Job -ScriptBlock {
        Set-Location "D:\server\htdocs\Domain_project\engeb"
        php artisan serve --host=127.0.0.1 --port=8000
    }
    
    Start-Sleep -Seconds 5
    
    if (Test-ServerConnection "http://127.0.0.1:8000") {
        Write-Host ""
        Write-Host "========================================" -ForegroundColor Green
        Write-Host "    تم تشغيل السيرفر بنجاح!" -ForegroundColor Green
        Write-Host "========================================" -ForegroundColor Green
        Write-Host "الرابط: http://127.0.0.1:8000" -ForegroundColor Cyan
        Write-Host "API: http://127.0.0.1:8000/api/v1/" -ForegroundColor Cyan
        Write-Host "Categories: http://127.0.0.1:8000/api/v1/categories/main" -ForegroundColor Cyan
        Write-Host "========================================" -ForegroundColor Green
        Write-Host ""
        
        Start-Process "http://127.0.0.1:8000"
        Read-Host "اضغط Enter لإيقاف السيرفر"
        Stop-Job $job
        Remove-Job $job
    } else {
        Write-Host ""
        Write-Host "خطأ: فشل في تشغيل السيرفر" -ForegroundColor Red
        Write-Host "تأكد من:" -ForegroundColor Red
        Write-Host "1. تثبيت PHP" -ForegroundColor Red
        Write-Host "2. تثبيت Composer" -ForegroundColor Red
        Write-Host "3. تشغيل composer install" -ForegroundColor Red
        Write-Host "4. تشغيل php artisan migrate" -ForegroundColor Red
        Write-Host ""
        Read-Host "اضغط Enter للإغلاق"
    }
}
