@extends('admin.layouts.app')

@section('title', 'الإشعارات المقروءة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">الإشعارات المقروءة</h1>
                    <p class="text-muted">الإشعارات التي تم قراءتها والاطلاع عليها</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>جميع الإشعارات
                    </a>
                    <a href="{{ route('admin.notifications.unread') }}" class="btn btn-outline-warning">
                        <i class="fas fa-bell me-2"></i>الإشعارات غير المقروءة
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
                            <i class="fas fa-bell me-2"></i>
                            الإشعارات المقروءة ({{ $notifications->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-success">مقروءة</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                                @php
                                                    $iconMap = [
                                                        'order' => 'shopping-cart',
                                                        'contact' => 'envelope',
                                                        'user' => 'user',
                                                        'product' => 'box',
                                                        'system' => 'cog',
                                                        'default' => 'bell'
                                                    ];
                                                    $icon = $iconMap[$notification->type] ?? $iconMap['default'];
                                                @endphp
                                                <i class="fas fa-{{ $icon }} text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="mb-0 fw-bold">{{ $notification->title }}</h6>
                                                    @php
                                                        $typeLabels = [
                                                            'order' => 'طلب',
                                                            'contact' => 'رسالة',
                                                            'user' => 'مستخدم',
                                                            'product' => 'منتج',
                                                            'system' => 'نظام'
                                                        ];
                                                        $typeColors = [
                                                            'order' => 'primary',
                                                            'contact' => 'info',
                                                            'user' => 'success',
                                                            'product' => 'warning',
                                                            'system' => 'secondary'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $typeColors[$notification->type] ?? 'secondary' }} fs-6">
                                                        {{ $typeLabels[$notification->type] ?? $notification->type }}
                                                    </span>
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check-circle me-1"></i>مقروءة
                                                    </span>
                                                </div>
                                                <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                                <div class="d-flex align-items-center gap-3 text-muted">
                                                    <small>
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $notification->created_at->format('Y-m-d H:i') }}
                                                    </small>
                                                    <small>
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                    @if($notification->read_at)
                                                        <small class="text-success">
                                                            <i class="fas fa-eye me-1"></i>
                                                            قُرئت {{ $notification->read_at->diffForHumans() }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.notifications.show', $notification) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        title="تمييز كغير مقروءة"
                                                        onclick="markAsUnread({{ $notification->id }})">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="حذف"
                                                        onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($notifications->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        عرض {{ $notifications->firstItem() }} إلى {{ $notifications->lastItem() }} من {{ $notifications->total() }} إشعار
                                    </div>
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد إشعارات مقروءة</h5>
                            <p class="text-muted">لم يتم قراءة أي إشعارات بعد</p>
                            <a href="{{ route('admin.notifications.unread') }}" class="btn btn-primary">
                                <i class="fas fa-bell me-2"></i>عرض الإشعارات غير المقروءة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsUnread(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
        fetch(`/admin/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
