@extends('admin.layouts.app')

@section('title', 'الرسائل المقروءة')

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
    background-color: rgba(40, 167, 69, 0.1) !important;
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

.badge.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
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
.pagination-links .pagination {
    margin: 0;
    gap: 5px;
}

.pagination-links .page-item {
    margin: 0 2px;
}

.pagination-links .page-link {
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

.pagination-links .page-link:hover {
    border-color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
}

.pagination-links .page-item.active .page-link {
    background: linear-gradient(135deg, #28a745, #20c997);
    border-color: #28a745;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.pagination-links .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
}

.pagination-links .page-item.disabled .page-link:hover {
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
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">الرسائل المقروءة</h1>
                    <p class="text-muted">الرسائل التي تم قراءتها والرد عليها</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>جميع الرسائل
                    </a>
                    <a href="{{ route('admin.contact-messages.unread') }}" class="btn btn-outline-warning">
                        <i class="fas fa-envelope me-2"></i>الرسائل غير المقروءة
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
                            <i class="fas fa-envelope-open me-2"></i>
                            الرسائل المقروءة ({{ $messages->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-success">مقروءة</span>
                            <span class="badge bg-light text-info">تم الرد</span>
                            <span class="badge bg-light text-secondary">مغلقة</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">المرسل</th>
                                        <th class="border-0">الموضوع</th>
                                        <th class="border-0">الحالة</th>
                                        <th class="border-0">تاريخ الإرسال</th>
                                        <th class="border-0">تاريخ القراءة</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $message->name }}</strong>
                                                    <small class="text-muted">{{ $message->email }}</small>
                                                    @if($message->phone)
                                                        <small class="text-muted">{{ $message->phone }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $message->subject }}</strong>
                                                    <small class="text-muted">{{ Str::limit($message->message, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'read' => ['class' => 'success', 'icon' => 'envelope-open', 'text' => 'مقروءة'],
                                                        'replied' => ['class' => 'info', 'icon' => 'reply', 'text' => 'تم الرد'],
                                                        'closed' => ['class' => 'secondary', 'icon' => 'times-circle', 'text' => 'مغلقة']
                                                    ];
                                                    $config = $statusConfig[$message->status] ?? ['class' => 'primary', 'icon' => 'envelope', 'text' => $message->status];
                                                @endphp
                                                <span class="badge bg-{{ $config['class'] }} fs-6">
                                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $message->created_at->format('Y-m-d H:i') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    {{ $message->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-success">
                                                    @if($message->read_at)
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ $message->read_at->format('Y-m-d H:i') }}
                                                        <small class="d-block">{{ $message->read_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">غير محدد</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($message->status !== 'replied')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-info" 
                                                                title="تمييز كرد عليها"
                                                                onclick="markAsReplied({{ $message->id }})">
                                                            <i class="fas fa-reply"></i>
                                                        </button>
                                                    @endif
                                                    @if($message->status !== 'closed')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary" 
                                                                title="إغلاق الرسالة"
                                                                onclick="markAsClosed({{ $message->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
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
                        
                        @if($messages->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        عرض {{ $messages->firstItem() }} إلى {{ $messages->lastItem() }} من {{ $messages->total() }} رسالة
                                    </div>
                                    <div class="pagination-links">
                                        {{ $messages->links('admin.partials.pagination') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد رسائل مقروءة</h5>
                            <p class="text-muted">لم يتم قراءة أي رسائل بعد</p>
                            <a href="{{ route('admin.contact-messages.unread') }}" class="btn btn-primary">
                                <i class="fas fa-envelope me-2"></i>عرض الرسائل غير المقروءة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsReplied(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-replied`, {
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

function markAsClosed(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-closed`, {
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
