@extends('admin.layouts.app')

@section('title', 'الطلبات المكتملة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">الطلبات المكتملة</h1>
                    <p class="text-muted">الطلبات المسلمة والملغية</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>جميع الطلبات
                    </a>
                    <a href="{{ route('admin.orders.pending') }}" class="btn btn-outline-warning">
                        <i class="fas fa-clock me-2"></i>الطلبات المعلقة
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            الطلبات المكتملة ({{ $orders->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-success">تم التسليم</span>
                            <span class="badge bg-light text-danger">ملغي</span>
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
                                        <th class="border-0">تاريخ الإكمال</th>
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
                                                        'delivered' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'تم التسليم'],
                                                        'cancelled' => ['class' => 'danger', 'icon' => 'times', 'text' => 'ملغي']
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
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @if($order->status === 'delivered' && $order->delivered_at)
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        {{ $order->delivered_at->format('Y-m-d H:i') }}
                                                        <small class="d-block">{{ $order->delivered_at->diffForHumans() }}</small>
                                                    @elseif($order->status === 'cancelled' && $order->cancelled_at)
                                                        <i class="fas fa-times-circle text-danger me-1"></i>
                                                        {{ $order->cancelled_at->format('Y-m-d H:i') }}
                                                        <small class="d-block">{{ $order->cancelled_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">غير محدد</span>
                                                    @endif
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
                                                    @if($order->status === 'cancelled' && $order->cancellation_reason)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-info" 
                                                                title="سبب الإلغاء"
                                                                onclick="showCancellationReason('{{ addslashes($order->cancellation_reason) }}')">
                                                            <i class="fas fa-info-circle"></i>
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
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        عرض {{ $orders->firstItem() }} إلى {{ $orders->lastItem() }} من {{ $orders->total() }} طلب
                                    </div>
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد طلبات مكتملة</h5>
                            <p class="text-muted">لم يتم إكمال أي طلبات بعد</p>
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

<!-- Cancellation Reason Modal -->
<div class="modal fade" id="cancellationReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سبب إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="cancellationReasonText"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
function showCancellationReason(reason) {
    document.getElementById('cancellationReasonText').textContent = reason;
    const modal = new bootstrap.Modal(document.getElementById('cancellationReasonModal'));
    modal.show();
}
</script>
@endsection
