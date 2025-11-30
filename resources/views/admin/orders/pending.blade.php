@extends('admin.layouts.app')

@section('title', 'الطلبات المعلقة')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header with Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 font-weight-bold">
                                <i class="fas fa-clock me-3"></i>الطلبات المعلقة
                            </h1>
                            <p class="mb-0 opacity-75">إدارة ومتابعة الطلبات قيد التنفيذ والمعلقة</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-list me-2"></i>جميع الطلبات
                                </a>
                                <a href="{{ route('admin.orders.completed') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-check-circle me-2"></i>المكتملة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 font-weight-bold">{{ $orders->total() }}</h4>
                            <p class="mb-0 opacity-75">إجمالي المعلقة</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $todayOrders = $orders->where('created_at', '>=', today())->count();
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $todayOrders }}</h4>
                            <p class="mb-0 opacity-75">طلبات اليوم</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $totalAmount = $orders->sum('total_amount');
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ number_format($totalAmount, 0) }}</h4>
                            <p class="mb-0 opacity-75">إجمالي القيمة (د.ك)</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $avgAmount = $orders->count() > 0 ? $totalAmount / $orders->count() : 0;
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ number_format($avgAmount, 1) }}</h4>
                            <p class="mb-0 opacity-75">متوسط الطلب (د.ك)</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-lg">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-dark">
                                <i class="fas fa-list-alt me-2 text-primary"></i>
                                قائمة الطلبات المعلقة
                            </h5>
                            <p class="mb-0 text-muted small">إجمالي {{ $orders->total() }} طلب معلق</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-hourglass-half me-1"></i>قيد الانتظار
                            </span>
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-check me-1"></i>مؤكد
                            </span>
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-cogs me-1"></i>قيد التحضير
                            </span>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>جاهز
                            </span>
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="fas fa-truck me-1"></i>قيد التوصيل
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">رقم الطلب</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">المورد</th>
                                        <th class="border-0">الحالة</th>
                                        <th class="border-0">المبلغ الإجمالي</th>
                                        <th class="border-0">تاريخ الطلب</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-primary">{{ $order->order_number }}</strong>
                                                    <small class="text-muted">ID: {{ $order->id }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $order->user->name ?? 'غير محدد' }}</strong>
                                                    <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                                    <small class="text-muted">{{ $order->user->phone ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-store text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $order->vendor->name ?? 'غير محدد' }}</strong>
                                                        <small class="text-muted d-block">{{ $order->vendor->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'قيد الانتظار'],
                                                        'confirmed' => ['class' => 'info', 'icon' => 'check', 'text' => 'مؤكد'],
                                                        'preparing' => ['class' => 'primary', 'icon' => 'cog', 'text' => 'قيد التحضير'],
                                                        'ready' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'جاهز'],
                                                        'shipped' => ['class' => 'info', 'icon' => 'truck', 'text' => 'قيد التوصيل'],
                                                    ];
                                                    $config = $statusConfig[$order->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => $order->status_label];
                                                @endphp
                                                <span class="badge bg-{{ $config['class'] }} fs-6">
                                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success fs-6">{{ number_format($order->total_amount, 3) }} د.ك</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    {{ $order->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.orders.pdf', $order) }}" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       title="تحميل PDF"
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    @if($order->canBeCancelled())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="إلغاء الطلب"
                                                                onclick="cancelOrder({{ $order->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($orders->hasPages())
                            <div class="card-footer border-0 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="text-muted d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2 text-primary"></i>
                                            عرض <strong>{{ $orders->firstItem() }}</strong> إلى <strong>{{ $orders->lastItem() }}</strong> من إجمالي <strong>{{ $orders->total() }}</strong> طلب
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            {{ $orders->links('admin.partials.pagination') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد طلبات معلقة</h5>
                            <p class="text-muted">جميع الطلبات تم تنفيذها أو إلغاؤها</p>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>عرض جميع الطلبات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        هل أنت متأكد من إلغاء هذا الطلب؟ لا يمكن التراجع عن هذا الإجراء.
                    </div>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">سبب الإلغاء (اختياري)</label>
                        <textarea class="form-control" id="cancellation_reason" name="reason" rows="3" 
                                  placeholder="اكتب سبب إلغاء الطلب..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>إلغاء الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    const form = document.getElementById('cancelOrderForm');
    form.action = `/admin/orders/${orderId}/cancel`;
    
    const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    modal.show();
}
</script>
@endsection
