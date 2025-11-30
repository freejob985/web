@extends('admin.layouts.app')

@section('title', 'إحصائيات النشرة الإخبارية')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 font-weight-bold">
                                <i class="fas fa-chart-bar me-3"></i>إحصائيات النشرة الإخبارية
                            </h1>
                            <p class="mb-0 opacity-75">تقرير شامل عن أداء النشرة الإخبارية والمشتركين</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.newsletters.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-users me-2"></i>المشتركين
                                </a>
                                <a href="{{ route('admin.newsletters.campaigns') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-bullhorn me-2"></i>الحملات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        // Get actual stats or use defaults if not available
        $stats = $stats ?? [
            'total_subscribers' => 1,
            'active_subscribers' => 1,
            'unsubscribed_subscribers' => 0,
            'total_campaigns' => 0,
            'sent_campaigns' => 0,
            'total_emails_sent' => 0,
            'open_rate' => 0,
            'click_rate' => 0,
            'bounce_rate' => 0,
            'growth_rate' => 0
        ];
    @endphp

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 font-weight-bold">{{ number_format($stats['total_subscribers']) }}</h3>
                            <p class="mb-0 opacity-75">إجمالي المشتركين</p>
                            <small class="opacity-50">
                                <i class="fas fa-arrow-up me-1"></i>+{{ $stats['growth_rate'] }}% هذا الشهر
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 font-weight-bold">{{ number_format($stats['active_subscribers']) }}</h3>
                            <p class="mb-0 opacity-75">المشتركين النشطين</p>
                            <small class="opacity-50">
                                {{ round(($stats['active_subscribers'] / $stats['total_subscribers']) * 100, 1) }}% من الإجمالي
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 font-weight-bold">{{ number_format($stats['total_campaigns']) }}</h3>
                            <p class="mb-0 opacity-75">إجمالي الحملات</p>
                            <small class="opacity-50">
                                {{ $stats['sent_campaigns'] }} حملة مرسلة
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-bullhorn fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 font-weight-bold">{{ number_format($stats['total_emails_sent']) }}</h3>
                            <p class="mb-0 opacity-75">إجمالي الرسائل المرسلة</p>
                            <small class="opacity-50">
                                {{ round($stats['total_emails_sent'] / $stats['total_campaigns']) }} متوسط لكل حملة
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="progress-circle mx-auto" data-percentage="{{ $stats['open_rate'] }}">
                            <div class="progress-circle-inner">
                                <span class="h4 font-weight-bold text-success">{{ $stats['open_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-bold text-dark">معدل الفتح</h6>
                    <p class="text-muted small mb-0">نسبة المشتركين الذين فتحوا الرسائل</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="progress-circle mx-auto" data-percentage="{{ $stats['click_rate'] }}">
                            <div class="progress-circle-inner">
                                <span class="h4 font-weight-bold text-primary">{{ $stats['click_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-bold text-dark">معدل النقر</h6>
                    <p class="text-muted small mb-0">نسبة المشتركين الذين نقروا على الروابط</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="progress-circle mx-auto" data-percentage="{{ $stats['bounce_rate'] }}">
                            <div class="progress-circle-inner">
                                <span class="h4 font-weight-bold text-warning">{{ $stats['bounce_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-bold text-dark">معدل الارتداد</h6>
                    <p class="text-muted small mb-0">نسبة الرسائل التي لم تصل للمشتركين</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="progress-circle mx-auto" data-percentage="{{ $stats['unsubscribed_subscribers'] / $stats['total_subscribers'] * 100 }}">
                            <div class="progress-circle-inner">
                                <span class="h4 font-weight-bold text-danger">{{ $stats['unsubscribed_subscribers'] }}</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-bold text-dark">إلغاء الاشتراك</h6>
                    <p class="text-muted small mb-0">عدد المشتركين الذين ألغوا اشتراكهم</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        نمو المشتركين (آخر 12 شهر)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subscribersChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-pie-chart me-2 text-primary"></i>
                        توزيع المشتركين
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="subscribersDistribution" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-history me-2 text-primary"></i>
                        النشاط الأخير
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>النشاط</th>
                                    <th>التفاصيل</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fas fa-user-plus text-success me-2"></i>
                                        اشتراك جديد
                                    </td>
                                    <td>ahmed@example.com</td>
                                    <td>منذ 5 دقائق</td>
                                    <td><span class="badge bg-success">مؤكد</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-paper-plane text-primary me-2"></i>
                                        إرسال حملة
                                    </td>
                                    <td>عروض الأسبوع الجديدة</td>
                                    <td>منذ ساعتين</td>
                                    <td><span class="badge bg-primary">مرسلة</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-user-minus text-warning me-2"></i>
                                        إلغاء اشتراك
                                    </td>
                                    <td>sara@example.com</td>
                                    <td>منذ 3 ساعات</td>
                                    <td><span class="badge bg-warning">ملغي</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-envelope-open text-info me-2"></i>
                                        فتح رسالة
                                    </td>
                                    <td>عروض نهاية الأسبوع</td>
                                    <td>منذ 4 ساعات</td>
                                    <td><span class="badge bg-info">مفتوحة</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#3498db 0deg, #3498db calc(var(--percentage) * 3.6deg), #e9ecef calc(var(--percentage) * 3.6deg), #e9ecef 360deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.progress-circle-inner {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set progress circle percentages
    document.querySelectorAll('.progress-circle').forEach(circle => {
        const percentage = circle.dataset.percentage;
        circle.style.setProperty('--percentage', percentage);
    });
    
    // Subscribers Growth Chart
    const ctx1 = document.getElementById('subscribersChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            datasets: [{
                label: 'المشتركين الجدد',
                data: [65, 78, 90, 81, 95, 105, 120, 110, 125, 140, 155, 170],
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Subscribers Distribution Chart
    const ctx2 = document.getElementById('subscribersDistribution').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['نشط', 'غير نشط', 'ملغي'],
            datasets: [{
                data: [{{ $stats['active_subscribers'] }}, {{ $stats['total_subscribers'] - $stats['active_subscribers'] - $stats['unsubscribed_subscribers'] }}, {{ $stats['unsubscribed_subscribers'] }}],
                backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection