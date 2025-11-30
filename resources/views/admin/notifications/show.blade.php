@extends('admin.layouts.app')

@section('title', 'تفاصيل الإشعار')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-bell me-2"></i>تفاصيل الإشعار
                    </h3>
                    <div class="d-flex gap-2">
                        @if(!$notification->is_read)
                            <button class="btn btn-success btn-sm" onclick="markAsRead({{ $notification->id }})">
                                <i class="fas fa-check me-1"></i>تمييز كمقروء
                            </button>
                        @else
                            <button class="btn btn-warning btn-sm" onclick="markAsUnread({{ $notification->id }})">
                                <i class="fas fa-eye-slash me-1"></i>تمييز كغير مقروء
                            </button>
                        @endif
                        <button class="btn btn-danger btn-sm" onclick="deleteNotification({{ $notification->id }})">
                            <i class="fas fa-trash me-1"></i>حذف
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Notification Details -->
                            <div class="notification-detail">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="notification-icon-large me-4">
                                        <i class="{{ $notification->icon }} fa-2x text-{{ $notification->color }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="mb-2">{{ $notification->title }}</h4>
                                        <p class="text-muted mb-3">{{ $notification->message }}</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-{{ $notification->color }} fs-6">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $notification->formatted_created_at }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-history me-1"></i>{{ $notification->time_ago }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Data -->
                                @if($notification->data && count($notification->data) > 0)
                                    <div class="mt-4">
                                        <h6 class="mb-3">بيانات إضافية:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    @foreach($notification->data as $key => $value)
                                                        <tr>
                                                            <td class="fw-bold">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                            <td>
                                                                @if(is_array($value))
                                                                    <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                @else
                                                                    {{ $value }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Notification Info -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">معلومات الإشعار</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">النوع:</label>
                                        <p class="mb-0">{{ ucfirst($notification->type) }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الحالة:</label>
                                        <p class="mb-0">
                                            @if($notification->is_read)
                                                <span class="badge bg-success">مقروء</span>
                                            @else
                                                <span class="badge bg-warning">غير مقروء</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                        <p class="mb-0">{{ $notification->formatted_created_at }}</p>
                                    </div>
                                    
                                    @if($notification->read_at)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">تاريخ القراءة:</label>
                                            <p class="mb-0">{{ $notification->read_at->format('Y-m-d H:i:s') }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">اللون:</label>
                                        <p class="mb-0">
                                            <span class="badge bg-{{ $notification->color }}">{{ ucfirst($notification->color) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">إجراءات سريعة</h6>
                                </div>
                                <div class="card-body">
                                    @if($notification->type === 'order' && isset($notification->data['order_id']))
                                        <a href="{{ route('admin.orders.show', $notification->data['order_id']) }}" class="btn btn-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-shopping-cart me-1"></i>عرض الطلب
                                        </a>
                                    @endif
                                    
                                    @if($notification->type === 'contact' && isset($notification->data['contact_message_id']))
                                        <a href="{{ route('admin.contact-messages.show', $notification->data['contact_message_id']) }}" class="btn btn-info btn-sm w-100 mb-2">
                                            <i class="fas fa-envelope me-1"></i>عرض الرسالة
                                        </a>
                                    @endif
                                    
                                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="window.print()">
                                        <i class="fas fa-print me-1"></i>طباعة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-detail {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.notification-icon-large {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@media print {
    .card-header .d-flex,
    .btn,
    .notification-actions {
        display: none !important;
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
                window.location.href = '/admin/notifications';
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection
