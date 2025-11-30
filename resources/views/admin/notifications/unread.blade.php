@extends('admin.layouts.app')

@section('title', 'الإشعارات غير المقروءة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">الإشعارات غير المقروءة</h1>
                    <p class="text-muted">الإشعارات الجديدة التي تحتاج إلى اهتمام</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>جميع الإشعارات
                    </a>
                    <a href="{{ route('admin.notifications.read') }}" class="btn btn-outline-success">
                        <i class="fas fa-bell me-2"></i>الإشعارات المقروءة
                    </a>
                    @if($notifications->total() > 0)
                        <button class="btn btn-warning" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-2"></i>تمييز الكل كمقروء
                        </button>
                    @endif
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
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            الإشعارات غير المقروءة ({{ $notifications->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-warning">جديدة</span>
                            <span class="badge bg-light text-danger">عاجلة</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action border-start border-warning border-4">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <div class="position-relative me-3">
                                                @php
                                                    $typeColors = [
                                                        'order' => 'primary',
                                                        'contact' => 'info',
                                                        'user' => 'success',
                                                        'product' => 'warning',
                                                        'system' => 'secondary'
                                                    ];
                                                    $bgColor = $typeColors[$notification->type] ?? 'warning';
                                                @endphp
                                                <div class="avatar-sm bg-{{ $bgColor }} rounded-circle d-flex align-items-center justify-content-center">
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
                                                <!-- Unread indicator -->
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                    <span class="visually-hidden">غير مقروء</span>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $notification->title }}</h6>
                                                    @php
                                                        $typeLabels = [
                                                            'order' => 'طلب',
                                                            'contact' => 'رسالة',
                                                            'user' => 'مستخدم',
                                                            'product' => 'منتج',
                                                            'system' => 'نظام'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $bgColor }} fs-6">
                                                        {{ $typeLabels[$notification->type] ?? $notification->type }}
                                                    </span>
                                                    @php
                                                        $isUrgent = str_contains(strtolower($notification->title), 'عاجل') || 
                                                                   str_contains(strtolower($notification->message), 'عاجل') ||
                                                                   $notification->created_at->diffInMinutes() < 30;
                                                    @endphp
                                                    @if($isUrgent)
                                                        <span class="badge bg-danger fs-6 animate-pulse">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>عاجل
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning fs-6">
                                                            <i class="fas fa-bell me-1"></i>جديد
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="mb-2 text-dark fw-medium">{{ $notification->message }}</p>
                                                <div class="d-flex align-items-center gap-3 text-muted">
                                                    <small>
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $notification->created_at->format('Y-m-d H:i') }}
                                                    </small>
                                                    <small class="fw-bold {{ $notification->created_at->diffInHours() < 1 ? 'text-danger' : 'text-warning' }}">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                @if($notification->data && is_array($notification->data))
                                                    <div class="mt-2">
                                                        @foreach($notification->data as $key => $value)
                                                            <small class="badge bg-light text-dark me-1">{{ $key }}: {{ $value }}</small>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.notifications.show', $notification) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="قراءة الإشعار">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-success" 
                                                        title="تمييز كمقروء"
                                                        onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i>
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
                        
                        @if($notifications->total() > 0)
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success" onclick="markAllAsRead()">
                                            <i class="fas fa-check-double me-1"></i>تمييز الكل كمقروء
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteAll()">
                                            <i class="fas fa-trash me-1"></i>حذف الكل
                                        </button>
                                    </div>
                                    <div class="text-muted">
                                        عرض {{ $notifications->firstItem() }} إلى {{ $notifications->lastItem() }} من {{ $notifications->total() }} إشعار
                                    </div>
                                </div>
                                @if($notifications->hasPages())
                                    <div class="mt-3">
                                        {{ $notifications->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">ممتاز! لا توجد إشعارات غير مقروءة</h5>
                            <p class="text-muted">تم قراءة جميع الإشعارات</p>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>عرض جميع الإشعارات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>

<script>
function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
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

function markAllAsRead() {
    if (confirm('هل تريد تمييز جميع الإشعارات كمقروءة؟')) {
        fetch('/admin/notifications/mark-all-read', {
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

function deleteAll() {
    if (confirm('هل تريد حذف جميع الإشعارات غير المقروءة؟ لا يمكن التراجع عن هذا الإجراء.')) {
        // This would require a new endpoint to delete all unread notifications
        alert('سيتم تنفيذ هذه الوظيفة قريباً');
    }
}
</script>
@endsection
