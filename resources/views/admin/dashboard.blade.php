@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-box text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">جميع المنتجات</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['products']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary bg-opacity-10 border-0">
                <small class="text-primary">+{{ number_format($stats['active_products']) }} نشط</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-store text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">الموردون</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['vendors']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success bg-opacity-10 border-0">
                <small class="text-success">مفعلون</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-users text-info fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">العملاء</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['users']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info bg-opacity-10 border-0">
                <small class="text-info">مستخدمون مسجلون</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-shopping-cart text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">الطلبات</h6>
                        <h3 class="mb-0 fw-bold">{{ number_format($stats['orders']) }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning bg-opacity-10 border-0">
                <small class="text-warning">{{ number_format($stats['pending_orders']) }} قيد الانتظار</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2 text-primary"></i>أحدث الطلبات</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>الحالة</th>
                                <th>المبلغ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary bg-opacity-10 rounded-circle me-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            {{ optional($order->user)->name ?? 'زائر' }}
                                            <br>
                                            <small class="text-muted">{{ optional($order->user)->email ?? 'غير متوفر' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($order->total_amount, 3) }}</span>
                                    <small class="text-muted">د.ك</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-success"></i>إجمالي المبيعات</h5>
            </div>
            <div class="card-body text-center">
                <h2 class="display-5 text-primary fw-bold">{{ number_format($stats['sales_total'], 3) }} <small class="fs-6">د.ك</small></h2>
                <p class="text-muted">آخر 7 أيام</p>
                
                <div class="d-flex justify-content-between align-items-end mt-4" style="height: 120px;">
                    @foreach($salesLast7Days as $day => $total)
                        @php $height = $salesMax > 0 ? max(10, intval(($total / $salesMax) * 100)) : 10; @endphp
                        <div class="text-center" style="flex: 1;">
                            <div class="bg-primary mx-auto rounded-top" 
                                 style="width: 30px; height: {{ $height }}px; margin: 0 auto;">
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ \Carbon\Carbon::parse($day)->format('d/m') }}
                            </small>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" 
                             role="progressbar" 
                             style="width: {{ $stats['sales_total'] > 0 ? min(100, ($stats['sales_total'] / 10000) * 100) : 0 }}%" 
                             aria-valuenow="{{ $stats['sales_total'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="10000">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">الهدف الشهري</small>
                        <small class="text-muted">10,000 د.ك</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-boxes me-2 text-info"></i>أفضل المنتجات</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-cog me-1"></i>إدارة
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>المنتج</th>
                                <th>المبيعات</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($topProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded me-2" 
                                                 width="40" 
                                                 height="40">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            {{ $product->name }}
                                            <br>
                                            <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $product->sales_count }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($product->price, 3) }}</span>
                                    <small class="text-muted">د.ك</small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-tasks me-2 text-warning"></i>حالة الطلبات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>الحالة</th>
                                <th>العدد</th>
                                <th>النسبة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($statusLabels as $key => $label)
                            @php $count = $orderStatusCounts[$key] ?? 0; @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    <span class="fw-bold">{{ $count }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $key == 'pending' ? 'warning' : ($key == 'completed' ? 'success' : 'secondary') }}" 
                                             role="progressbar" 
                                             style="width: {{ $stats['orders'] > 0 ? ($count / $stats['orders']) * 100 : 0 }}%" 
                                             aria-valuenow="{{ $count }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $stats['orders'] }}">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" 
                                       href="{{ route('admin.orders.index', ['status' => $key]) }}">
                                        <i class="fas fa-eye me-1"></i>عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-danger"></i>روابط سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-plus-circle fs-2 mb-2"></i>
                            <span>إضافة منتج</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-boxes fs-2 mb-2"></i>
                            <span>إدارة المنتجات</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-shopping-cart fs-2 mb-2"></i>
                            <span>إدارة الطلبات</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.vendors.create') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-store fs-2 mb-2"></i>
                            <span>إضافة مورد</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-users fs-2 mb-2"></i>
                            <span>إدارة الموردين</span>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-user-friends fs-2 mb-2"></i>
                            <span>المستخدمون</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>نظرة عامة</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted">المنتجات</small>
                        <h5 class="mb-0">{{ number_format($stats['products']) }}</h5>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-box text-primary"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted">الموردون</small>
                        <h5 class="mb-0">{{ number_format($stats['vendors']) }}</h5>
                    </div>
                    <div class="bg-info bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-store text-info"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted">العملاء</small>
                        <h5 class="mb-0">{{ number_format($stats['users']) }}</h5>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-users text-success"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">إجمالي المبيعات</small>
                        <h5 class="mb-0">{{ number_format($stats['sales_total'], 3) }} د.ك</h5>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 rounded-circle">
                        <i class="fas fa-chart-line text-warning"></i>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">آخر تحديث</span>
                        <span>{{ now()->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection