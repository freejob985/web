@extends('admin.layouts.app')

@section('content')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }
    
    .stats-icon-wrapper {
        margin-bottom: 15px;
    }
    
    .stats-card .stats-icon {
        font-size: 2.5rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .stats-content {
        margin-bottom: 15px;
    }
    
    .stats-card .stats-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 8px 0;
        line-height: 1;
    }
    
    .stats-card .stats-label {
        font-size: 0.85rem;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .stats-trend {
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.75rem;
        opacity: 0.9;
    }
    
    .stats-trend i {
        margin-left: 5px;
        font-size: 0.8rem;
    }
    
    .trend-text {
        font-weight: 500;
    }
    
    .message-item {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .message-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .message-item.unread {
        border-left: 5px solid #e74c3c;
        background: rgba(231, 76, 60, 0.05);
    }
    
    .message-item.read {
        border-left: 5px solid #f39c12;
    }
    
    .message-item.replied {
        border-left: 5px solid #27ae60;
    }
    
    .message-item.closed {
        border-left: 5px solid #95a5a6;
        opacity: 0.7;
    }
    
    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }
    
    .message-meta {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .message-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border-color: #2980b9;
    }
    
    .filter-tab:not(.active) {
        background: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }
    
    .filter-tab:not(.active):hover {
        background: #e9ecef;
        color: #495057;
    }
    
    .search-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        color: #6c757d;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #adb5bd;
        margin-bottom: 30px;
    }
    
    /* Enhanced Pagination Styling */
    .pagination-wrapper {
        background: white;
        border-radius: 15px;
        padding: 20px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .pagination-info {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .custom-pagination .pagination-custom {
        margin: 0;
        gap: 5px;
    }
    
    .custom-pagination .page-item {
        margin: 0 2px;
    }
    
    .custom-pagination .page-link {
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
    
    .custom-pagination .page-link:hover {
        border-color: #3498db;
        background-color: rgba(52, 152, 219, 0.1);
        color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.2);
    }
    
    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border-color: #3498db;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }
    
    .custom-pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
    }
    
    .custom-pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    
    @media (max-width: 768px) {
        .pagination-wrapper {
            padding: 15px;
        }
        
        .pagination-wrapper .d-flex {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .custom-pagination .page-link {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-envelope me-3"></i>رسائل الاتصال</h2>
        <div class="d-flex gap-2">
            <button onclick="refreshMessages()" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt me-2"></i>تحديث
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-envelope stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['total'] ?? 0 }}</div>
                    <div class="stats-label">إجمالي الرسائل</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="trend-text">+12% من الشهر الماضي</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-exclamation-circle stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['new'] ?? 0 }}</div>
                    <div class="stats-label">رسائل جديدة</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-bell text-warning"></i>
                    <span class="trend-text">تحتاج انتباه</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-eye stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['read'] ?? 0 }}</div>
                    <div class="stats-label">مقروءة</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-check text-info"></i>
                    <span class="trend-text">تمت المراجعة</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-reply stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['replied'] ?? 0 }}</div>
                    <div class="stats-label">تم الرد</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-thumbs-up text-success"></i>
                    <span class="trend-text">مكتملة</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #95a5a6, #7f8c8d);">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-lock stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['closed'] ?? 0 }}</div>
                    <div class="stats-label">مغلقة</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-archive text-secondary"></i>
                    <span class="trend-text">مؤرشفة</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-bell stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $stats['unread'] ?? 0 }}</div>
                    <div class="stats-label">غير مقروءة</div>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-clock text-warning"></i>
                    <span class="trend-text">في الانتظار</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-box">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label">البحث:</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="البحث في الاسم، البريد، الموضوع أو الرسالة...">
            </div>
            <div class="col-md-3">
                <label class="form-label">ترتيب حسب:</label>
                <select name="sort_by" class="form-select">
                    <option value="created_at" @selected(request('sort_by') == 'created_at')>تاريخ الإرسال</option>
                    <option value="name" @selected(request('sort_by') == 'name')>الاسم</option>
                    <option value="subject" @selected(request('sort_by') == 'subject')>الموضوع</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الاتجاه:</label>
                <select name="sort_order" class="form-select">
                    <option value="desc" @selected(request('sort_order') == 'desc')>الأحدث أولاً</option>
                    <option value="asc" @selected(request('sort_order') == 'asc')>الأقدم أولاً</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-2"></i>بحث
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>مسح
                </a>
            </div>
        </form>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.contact-messages.index') }}" 
           class="filter-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
            <i class="fas fa-list me-2"></i>الكل ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" 
           class="filter-tab {{ request('status') == 'new' ? 'active' : '' }}">
            <i class="fas fa-exclamation-circle me-2"></i>جديدة ({{ $stats['new'] }})
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" 
           class="filter-tab {{ request('status') == 'read' ? 'active' : '' }}">
            <i class="fas fa-eye me-2"></i>مقروءة ({{ $stats['read'] }})
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}" 
           class="filter-tab {{ request('status') == 'replied' ? 'active' : '' }}">
            <i class="fas fa-reply me-2"></i>تم الرد ({{ $stats['replied'] }})
        </a>
        <a href="{{ route('admin.contact-messages.index', ['status' => 'closed']) }}" 
           class="filter-tab {{ request('status') == 'closed' ? 'active' : '' }}">
            <i class="fas fa-lock me-2"></i>مغلقة ({{ $stats['closed'] }})
        </a>
    </div>

    <!-- Messages List -->
    @if($messages->count() > 0)
        @foreach($messages as $message)
            <div class="message-item {{ $message->status }}">
                <div class="message-header">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-user me-2"></i>{{ $message->name }}
                            @if($message->is_unread)
                                <span class="badge badge-danger ms-2">جديد</span>
                            @endif
                        </h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-envelope me-1"></i>{{ $message->email }}
                            @if($message->phone)
                                | <i class="fas fa-phone me-1"></i>{{ $message->phone }}
                            @endif
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $message->status_badge_class }}">{{ $message->status_label }}</span>
                        <p class="text-muted small mb-0 mt-1">{{ $message->time_ago }}</p>
                    </div>
                </div>
                
                <div class="message-meta">
                    <strong>الموضوع:</strong> {{ $message->subject }}
                </div>
                
                <div class="mb-3">
                    <p class="mb-0">{{ Str::limit($message->message, 200) }}</p>
                </div>
                
                <div class="message-actions">
                    <a href="{{ route('admin.contact-messages.show', $message) }}" 
                       class="btn btn-sm btn-info">
                        <i class="fas fa-eye me-1"></i>عرض التفاصيل
                    </a>
                    
                    @if($message->status === 'new')
                        <button onclick="markAsRead({{ $message->id }})" 
                                class="btn btn-sm btn-warning">
                            <i class="fas fa-eye me-1"></i>تمييز كمقروء
                        </button>
                    @endif
                    
                    @if($message->status !== 'replied')
                        <button onclick="markAsReplied({{ $message->id }})" 
                                class="btn btn-sm btn-success">
                            <i class="fas fa-reply me-1"></i>تمييز كرد
                        </button>
                    @endif
                    
                    @if($message->status !== 'closed')
                        <button onclick="markAsClosed({{ $message->id }})" 
                                class="btn btn-sm btn-secondary">
                            <i class="fas fa-lock me-1"></i>إغلاق
                        </button>
                    @endif
                    
                    <button onclick="deleteMessage({{ $message->id }})" 
                            class="btn btn-sm btn-danger">
                        <i class="fas fa-trash me-1"></i>حذف
                    </button>
                </div>
            </div>
        @endforeach
        
        <!-- Enhanced Pagination -->
        <div class="pagination-wrapper mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <span class="text-muted">
                        عرض {{ $messages->firstItem() ?? 0 }} إلى {{ $messages->lastItem() ?? 0 }} من {{ $messages->total() }} رسالة
                    </span>
                </div>
                <div class="pagination-links">
                    {{ $messages->links('admin.partials.pagination') }}
                </div>
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-envelope"></i>
            <h4>لا توجد رسائل</h4>
            <p>لم يتم إرسال أي رسائل اتصال بعد.</p>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذه الرسالة؟</p>
                <p class="text-danger"><strong>تحذير:</strong> لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-read`, {
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
    });
}

function markAsReplied(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-replied`, {
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
    });
}

function markAsClosed(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-closed`, {
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
    });
}

function deleteMessage(messageId) {
    document.getElementById('deleteForm').action = `/admin/contact-messages/${messageId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function refreshMessages() {
    location.reload();
}
</script>
@endsection
