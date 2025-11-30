@extends('admin.layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-bell me-2"></i>الإشعارات
                    </h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-1"></i>تمييز الكل كمقروء
                        </button>
                        <a href="{{ route('admin.notifications.index', ['status' => 'unread']) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-eye-slash me-1"></i>غير المقروءة فقط
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row p-3">
                    <div class="col-md-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['total'] }}</h4>
                                <small>إجمالي الإشعارات</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['unread'] }}</h4>
                                <small>غير مقروءة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['read'] }}</h4>
                                <small>مقروءة</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['orders'] }}</h4>
                                <small>طلبات</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['contacts'] }}</h4>
                                <small>اتصال</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">جميع الأنواع</option>
                                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>الطلبات</option>
                                <option value="contact" {{ request('type') == 'contact' ? 'selected' : '' }}>الاتصال</option>
                                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>النظام</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>غير مقروءة</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>مقروءة</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="البحث في الإشعارات..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>بحث
                            </button>
                        </div>
                    </form>

                    <!-- Notifications List -->
                    <div class="notifications-list">
                        @forelse($notifications as $notification)
                            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" data-id="{{ $notification->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon me-3">
                                        <i class="{{ $notification->icon }} text-{{ $notification->color }}"></i>
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 {{ $notification->is_read ? '' : 'fw-bold' }}">
                                                    {{ $notification->title }}
                                                </h6>
                                                <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $notification->time_ago }}
                                                    <span class="badge bg-{{ $notification->color }} ms-2">{{ ucfirst($notification->type) }}</span>
                                                </small>
                                            </div>
                                            <div class="notification-actions">
                                                @if(!$notification->is_read)
                                                    <button class="btn btn-sm btn-outline-success me-1" onclick="markAsRead({{ $notification->id }})" title="تمييز كمقروء">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-warning me-1" onclick="markAsUnread({{ $notification->id }})" title="تمييز كغير مقروء">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-sm btn-outline-primary me-1" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد إشعارات</h5>
                                <p class="text-muted">لم يتم العثور على أي إشعارات تطابق معايير البحث</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.notification-item.unread {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
}

.notification-item.read {
    background-color: #ffffff;
    opacity: 0.8;
}

.notification-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.notification-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f8f9fa;
}

.notification-actions {
    display: flex;
    gap: 5px;
}

@media (max-width: 768px) {
    .notification-actions {
        flex-direction: column;
    }
    
    .notification-item {
        padding: 10px;
    }
}
</style>

<script>
function markAsRead(id) {
    fetch(`/admin/notifications/${id}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAsUnread(id) {
    fetch(`/admin/notifications/${id}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    if (confirm('هل أنت متأكد من تمييز جميع الإشعارات كمقروءة؟')) {
        fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function deleteNotification(id) {
    if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
        fetch(`/admin/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection
