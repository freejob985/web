@extends('admin.layouts.app')

@section('title', 'الرسائل غير المقروءة')

@section('content')
<style>
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
    padding: 15px 12px;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.1) !important;
    transform: scale(1.01);
}

.table tbody td {
    padding: 15px 12px;
    vertical-align: middle;
    border-color: #f1f3f4;
}

/* Enhanced Card Styling */
.card {
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.card-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 2px solid #dee2e6;
    padding: 20px 25px;
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

.badge.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

/* Enhanced Buttons */
.btn-group .btn {
    border-radius: 8px;
    margin: 0 2px;
    padding: 8px 12px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-sm {
    padding: 6px 10px;
    font-size: 0.75rem;
}

/* Avatar Styling */
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Enhanced Spacing */
.container-fluid {
    padding: 20px;
}

.mb-4 {
    margin-bottom: 2rem !important;
}

.gap-2 {
    gap: 0.75rem !important;
}

/* Custom Pagination Styling */
.pagination-custom {
    margin: 0;
    gap: 5px;
}

.pagination-custom .page-item {
    margin: 0 2px;
}

.pagination-custom .page-link {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    color: #6c757d;
    font-weight: 600;
    padding: 10px 15px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 45px;
    justify-content: center;
}

.pagination-custom .page-link:hover {
    border-color: #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
}

.pagination-custom .page-item.active .page-link {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    border-color: #ffc107;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.pagination-custom .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
}

.pagination-custom .page-item.disabled .page-link:hover {
    transform: none;
    box-shadow: none;
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 15px;
    }
    
    .table-responsive {
        border-radius: 10px;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 5px;
    }
    
    .card-header, .card-footer {
        padding: 15px;
    }
    
    .d-flex.gap-2 {
        flex-wrap: wrap;
        gap: 10px;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">الرسائل غير المقروءة</h1>
                    <p class="text-muted">الرسائل الجديدة التي تحتاج إلى قراءة</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>جميع الرسائل
                    </a>
                    <a href="{{ route('admin.contact-messages.read') }}" class="btn btn-outline-success">
                        <i class="fas fa-envelope-open me-2"></i>الرسائل المقروءة
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
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            الرسائل غير المقروءة ({{ $messages->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-warning">جديدة</span>
                            @if($messages->total() > 0)
                                <button class="btn btn-sm btn-light" onclick="markAllAsRead()">
                                    <i class="fas fa-check-double me-1"></i>تمييز الكل كمقروء
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th class="border-0">المرسل</th>
                                        <th class="border-0">الموضوع</th>
                                        <th class="border-0">الأولوية</th>
                                        <th class="border-0">تاريخ الإرسال</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr class="table-warning">
                                            <td>
                                                <input type="checkbox" class="message-checkbox" value="{{ $message->id }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-envelope text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $message->name }}</strong>
                                                        <small class="text-muted d-block">{{ $message->email }}</small>
                                                        @if($message->phone)
                                                            <small class="text-muted d-block">{{ $message->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-dark">{{ $message->subject }}</strong>
                                                    <small class="text-muted">{{ Str::limit($message->message, 60) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $priority = 'عادية';
                                                    $priorityClass = 'secondary';
                                                    
                                                    // Determine priority based on keywords or time
                                                    if (str_contains(strtolower($message->subject), 'عاجل') || str_contains(strtolower($message->message), 'عاجل')) {
                                                        $priority = 'عاجل';
                                                        $priorityClass = 'danger';
                                                    } elseif (str_contains(strtolower($message->subject), 'مهم') || str_contains(strtolower($message->message), 'مهم')) {
                                                        $priority = 'مهم';
                                                        $priorityClass = 'warning';
                                                    } elseif ($message->created_at->diffInHours() < 2) {
                                                        $priority = 'جديدة';
                                                        $priorityClass = 'info';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $priorityClass }} fs-6">{{ $priority }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $message->created_at->format('Y-m-d H:i') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <strong>{{ $message->created_at->diffForHumans() }}</strong>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="قراءة الرسالة">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success" 
                                                            title="تمييز كمقروءة"
                                                            onclick="markAsRead({{ $message->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info" 
                                                            title="رد سريع"
                                                            onclick="quickReply({{ $message->id }})">
                                                        <i class="fas fa-reply"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="حذف"
                                                            onclick="deleteMessage({{ $message->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($messages->total() > 0)
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-success" onclick="markSelectedAsRead()">
                                            <i class="fas fa-check me-1"></i>تمييز المحدد كمقروء
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteSelected()">
                                            <i class="fas fa-trash me-1"></i>حذف المحدد
                                        </button>
                                    </div>
                                    <div class="text-muted">
                                        عرض {{ $messages->firstItem() }} إلى {{ $messages->lastItem() }} من {{ $messages->total() }} رسالة
                                    </div>
                                </div>
                                @if($messages->hasPages())
                                    <div class="mt-3">
                                        {{ $messages->links('admin.partials.pagination') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">ممتاز! لا توجد رسائل غير مقروءة</h5>
                            <p class="text-muted">تم قراءة جميع الرسائل</p>
                            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>عرض جميع الرسائل
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function markAsRead(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-read`, {
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
    if (confirm('هل تريد تمييز جميع الرسائل كمقروءة؟')) {
        const messageIds = Array.from(document.querySelectorAll('.message-checkbox')).map(cb => cb.value);
        
        Promise.all(messageIds.map(id => 
            fetch(`/admin/contact-messages/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
        )).then(() => {
            location.reload();
        });
    }
}

function markSelectedAsRead() {
    const selected = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('يرجى تحديد رسائل للتمييز');
        return;
    }
    
    Promise.all(selected.map(id => 
        fetch(`/admin/contact-messages/${id}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
    )).then(() => {
        location.reload();
    });
}

function deleteSelected() {
    const selected = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('يرجى تحديد رسائل للحذف');
        return;
    }
    
    if (confirm(`هل تريد حذف ${selected.length} رسالة؟`)) {
        Promise.all(selected.map(id => 
            fetch(`/admin/contact-messages/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
        )).then(() => {
            location.reload();
        });
    }
}

function quickReply(messageId) {
    // Implement quick reply functionality
    alert('سيتم تنفيذ وظيفة الرد السريع قريباً');
}

function deleteMessage(messageId) {
    if (confirm('هل أنت متأكد من حذف هذه الرسالة؟')) {
        fetch(`/admin/contact-messages/${messageId}`, {
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
