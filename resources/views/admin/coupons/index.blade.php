@extends('admin.layouts.app')

@section('title', 'إدارة الكوبونات')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="fas fa-ticket-alt me-3 text-primary"></i>إدارة الكوبونات</h1>
            <p class="text-muted mb-0">إدارة كوبونات الخصم والعروض الترويجية</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-info" onclick="showCouponsGuide()">
                <i class="fas fa-question-circle me-2"></i>دليل الكوبونات
            </button>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة كوبون جديد
            </a>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="coupon-stats-card total-coupons">
                <div class="coupon-stats-body">
                    <div class="coupon-stats-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="coupon-stats-content">
                        <div class="coupon-stats-number">{{ $coupons->total() }}</div>
                        <div class="coupon-stats-label">إجمالي الكوبونات</div>
                    </div>
                    <div class="coupon-stats-footer">
                        <div class="coupon-stats-trend positive">
                            <i class="fas fa-chart-line"></i>
                            <span>جميع الكوبونات</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="coupon-stats-card active-coupons">
                <div class="coupon-stats-body">
                    <div class="coupon-stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="coupon-stats-content">
                        <div class="coupon-stats-number">{{ $coupons->where('status', 'active')->count() }}</div>
                        <div class="coupon-stats-label">الكوبونات النشطة</div>
                    </div>
                    <div class="coupon-stats-footer">
                        <div class="coupon-stats-trend positive">
                            <i class="fas fa-thumbs-up"></i>
                            <span>متاحة للاستخدام</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="coupon-stats-card expired-coupons">
                <div class="coupon-stats-body">
                    <div class="coupon-stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="coupon-stats-content">
                        <div class="coupon-stats-number">{{ $coupons->where('status', 'expired')->count() }}</div>
                        <div class="coupon-stats-label">الكوبونات المنتهية</div>
                    </div>
                    <div class="coupon-stats-footer">
                        <div class="coupon-stats-trend neutral">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>انتهت صلاحيتها</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="coupon-stats-card used-coupons">
                <div class="coupon-stats-body">
                    <div class="coupon-stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="coupon-stats-content">
                        <div class="coupon-stats-number">{{ $coupons->sum('used_count') }}</div>
                        <div class="coupon-stats-label">مرات الاستخدام</div>
                    </div>
                    <div class="coupon-stats-footer">
                        <div class="coupon-stats-trend positive">
                            <i class="fas fa-star"></i>
                            <span>إجمالي الاستخدام</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card coupon-management-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>فلترة وإدارة الكوبونات
                        </h5>
                        <div class="header-actions">
                            <button class="btn btn-sm btn-outline-success" onclick="exportCoupons()">
                                <i class="fas fa-download me-1"></i>تصدير
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="expired">منتهي الصلاحية</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="typeFilter">
                                <option value="">جميع الأنواع</option>
                                <option value="fixed">مبلغ ثابت</option>
                                <option value="percentage">نسبة مئوية</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput" placeholder="البحث بالكود أو الاسم...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary" onclick="applyFilters()">تصفية</button>
                        </div>
                    </div>

                    <!-- Coupons Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>الكود</th>
                                    <th>الاسم</th>
                                    <th>نوع الخصم</th>
                                    <th>قيمة الخصم</th>
                                    <th>الحد الأدنى</th>
                                    <th>الاستخدام</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>
                                        <code>{{ $coupon->code }}</code>
                                    </td>
                                    <td>{{ $coupon->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $coupon->discount_type == 'fixed' ? 'info' : 'success' }}">
                                            {{ $coupon->discount_type == 'fixed' ? 'مبلغ ثابت' : 'نسبة مئوية' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($coupon->discount_type == 'fixed')
                                            {{ number_format($coupon->discount_value, 3) }} دينار
                                        @else
                                            {{ $coupon->discount_value }}%
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->minimum_amount)
                                            {{ number_format($coupon->minimum_amount, 3) }} دينار
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $coupon->used_count }}
                                        @if($coupon->usage_limit_type == 'limited')
                                            / {{ $coupon->usage_limit }}
                                        @else
                                            / ∞
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $coupon->status == 'active' ? 'success' : ($coupon->status == 'expired' ? 'danger' : 'secondary') }}">
                                            {{ $coupon->status == 'active' ? 'نشط' : ($coupon->status == 'expired' ? 'منتهي' : 'غير نشط') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($coupon->expires_at)
                                            {{ $coupon->expires_at->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">لا ينتهي</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-{{ $coupon->status == 'active' ? 'secondary' : 'success' }}" 
                                                    onclick="toggleStatus({{ $coupon->id }})">
                                                <i class="fas fa-{{ $coupon->status == 'active' ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" 
                                                  style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد كوبونات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Coupon Statistics Cards */
.coupon-stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 0;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    transition: all 0.4s ease;
    overflow: hidden;
    position: relative;
}

.coupon-stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.coupon-stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.coupon-stats-card.total-coupons {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.coupon-stats-card.active-coupons {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.coupon-stats-card.expired-coupons {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.coupon-stats-card.used-coupons {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.coupon-stats-body {
    padding: 25px;
    position: relative;
    z-index: 2;
}

.coupon-stats-icon {
    text-align: center;
    margin-bottom: 20px;
}

.coupon-stats-icon i {
    font-size: 3rem;
    opacity: 0.9;
}

.coupon-stats-content {
    text-align: center;
    margin-bottom: 20px;
}

.coupon-stats-number {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 10px 0;
    line-height: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.coupon-stats-label {
    font-size: 0.9rem;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0;
}

.coupon-stats-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 15px;
    text-align: center;
}

.coupon-stats-trend {
    font-size: 0.8rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.coupon-stats-trend.positive {
    color: rgba(255, 255, 255, 0.9);
}

.coupon-stats-trend.neutral {
    color: rgba(255, 255, 255, 0.8);
}

.coupon-stats-trend i {
    font-size: 0.9rem;
}

/* Enhanced Card Styling */
.coupon-management-card {
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.coupon-management-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 20px 25px;
}

.coupon-management-card .card-body {
    padding: 25px;
}

/* Enhanced Table Styling */
.table {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.table thead th {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    font-weight: 600;
    text-align: center;
    padding: 15px 10px;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.1);
    transform: scale(1.01);
}

.table tbody td {
    padding: 15px 10px;
    vertical-align: middle;
    border-color: #f1f3f4;
}

/* Enhanced Badges */
.badge {
    font-size: 0.75rem;
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
}

.badge-success {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.badge-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.badge-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
}

/* Enhanced Buttons */
.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
    padding: 6px 10px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Enhanced Form Controls */
.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 10px 15px;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
    transform: translateY(-1px);
}

/* Code Styling */
code {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #e83e8c;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Header Actions */
.header-actions .btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
    .coupon-stats-number {
        font-size: 2.2rem;
    }
    
    .coupon-stats-body {
        padding: 20px;
    }
    
    .table-responsive {
        border-radius: 10px;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<script>
function toggleStatus(couponId) {
    fetch(`/admin/coupons/${couponId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم تغيير حالة الكوبون بنجاح', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('حدث خطأ: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (type) params.append('discount_type', type);
    if (search) params.append('search', search);
    
    window.location.href = '{{ route("admin.coupons.index") }}?' + params.toString();
}

function exportCoupons() {
    showNotification('جاري تصدير الكوبونات...', 'info');
    // Implement export functionality
    setTimeout(() => {
        showNotification('تم تصدير الكوبونات بنجاح', 'success');
    }, 2000);
}

function showCouponsGuide() {
    const guideModal = `
        <div class="modal fade" id="couponsGuideModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-question-circle me-2"></i>دليل إدارة الكوبونات
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="guide-section">
                            <h6><i class="fas fa-ticket-alt me-2 text-primary"></i>أنواع الكوبونات</h6>
                            <ul>
                                <li><strong>مبلغ ثابت:</strong> خصم بمبلغ محدد (مثال: 5 دنانير)</li>
                                <li><strong>نسبة مئوية:</strong> خصم بنسبة من المبلغ (مثال: 10%)</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-cogs me-2 text-info"></i>إعدادات الكوبون</h6>
                            <ul>
                                <li><strong>الحد الأدنى:</strong> أقل مبلغ للطلب لاستخدام الكوبون</li>
                                <li><strong>حد الاستخدام:</strong> عدد مرات الاستخدام المسموحة</li>
                                <li><strong>تاريخ الانتهاء:</strong> آخر موعد لاستخدام الكوبون</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-chart-bar me-2 text-success"></i>حالات الكوبون</h6>
                            <ul>
                                <li><strong>نشط:</strong> متاح للاستخدام</li>
                                <li><strong>غير نشط:</strong> معطل مؤقتاً</li>
                                <li><strong>منتهي:</strong> انتهت صلاحيته</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-lightbulb me-2 text-warning"></i>نصائح مهمة</h6>
                            <ul>
                                <li>استخدم أكواد واضحة وسهلة التذكر</li>
                                <li>حدد تواريخ انتهاء مناسبة</li>
                                <li>راقب استخدام الكوبونات بانتظام</li>
                                <li>اختبر الكوبونات قبل نشرها</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('couponsGuideModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', guideModal);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('couponsGuideModal'));
    modal.show();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add guide modal styles
const guideStyles = `
<style>
.guide-section {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.guide-section h6 {
    margin-bottom: 10px;
    font-weight: 600;
}

.guide-section ul {
    margin-bottom: 0;
    padding-right: 20px;
}

.guide-section li {
    margin-bottom: 5px;
    color: #6c757d;
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', guideStyles);
</script>
@endsection
