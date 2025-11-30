@extends('admin.layouts.app')

@section('content')
<style>
    .message-detail-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }
    
    .message-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
    }
    
    .message-content {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        border-left: 5px solid #3498db;
    }
    
    .message-meta {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .meta-item:last-child {
        border-bottom: none;
    }
    
    .meta-label {
        font-weight: 600;
        color: #6c757d;
    }
    
    .meta-value {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-new { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    .status-read { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
    .status-replied { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; }
    .status-closed { background: linear-gradient(135deg, #95a5a6, #7f8c8d); color: white; }
    
    .admin-notes {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Message Header -->
    <div class="message-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-envelope me-3"></i>
                    {{ $contactMessage->subject }}
                </h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-user me-2"></i>{{ $contactMessage->name }}
                    <span class="mx-2">|</span>
                    <i class="fas fa-calendar me-2"></i>{{ $contactMessage->formatted_created_at }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Message Details -->
        <div class="col-md-8">
            <div class="message-detail-card">
                <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>تفاصيل الرسالة</h4>
                
                <div class="message-content">
                    <h6 class="mb-3">محتوى الرسالة:</h6>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $contactMessage->message }}</p>
                </div>
                
                @if($contactMessage->admin_notes)
                    <div class="admin-notes">
                        <h6 class="mb-3"><i class="fas fa-sticky-note me-2"></i>ملاحظات الإدارة:</h6>
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $contactMessage->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Message Meta Information -->
        <div class="col-md-4">
            <div class="message-meta">
                <h5 class="mb-3"><i class="fas fa-user me-2"></i>معلومات المرسل</h5>
                
                <div class="meta-item">
                    <span class="meta-label">الاسم:</span>
                    <span class="meta-value">{{ $contactMessage->name }}</span>
                </div>
                
                <div class="meta-item">
                    <span class="meta-label">البريد الإلكتروني:</span>
                    <span class="meta-value">
                        <a href="mailto:{{ $contactMessage->email }}" class="text-primary">
                            {{ $contactMessage->email }}
                        </a>
                    </span>
                </div>
                
                @if($contactMessage->phone)
                <div class="meta-item">
                    <span class="meta-label">رقم الهاتف:</span>
                    <span class="meta-value">
                        <a href="tel:{{ $contactMessage->phone }}" class="text-primary">
                            {{ $contactMessage->phone }}
                        </a>
                    </span>
                </div>
                @endif
                
                <div class="meta-item">
                    <span class="meta-label">الموضوع:</span>
                    <span class="meta-value">{{ $contactMessage->subject }}</span>
                </div>
                
                <div class="meta-item">
                    <span class="meta-label">الحالة:</span>
                    <span class="status-badge status-{{ $contactMessage->status }}">
                        {{ $contactMessage->status_label }}
                    </span>
                </div>
                
                <div class="meta-item">
                    <span class="meta-label">تاريخ الإرسال:</span>
                    <span class="meta-value">{{ $contactMessage->formatted_created_at }}</span>
                </div>
                
                @if($contactMessage->read_at)
                <div class="meta-item">
                    <span class="meta-label">تاريخ القراءة:</span>
                    <span class="meta-value">{{ $contactMessage->read_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
                
                @if($contactMessage->replied_at)
                <div class="meta-item">
                    <span class="meta-label">تاريخ الرد:</span>
                    <span class="meta-value">{{ $contactMessage->replied_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
                
                <div class="meta-item">
                    <span class="meta-label">عنوان IP:</span>
                    <span class="meta-value">{{ $contactMessage->ip_address ?? 'غير محدد' }}</span>
                </div>
            </div>

            <!-- Status Update Form -->
            <div class="message-meta">
                <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>تحديث الحالة</h5>
                
                <form method="POST" action="{{ route('admin.contact-messages.update', $contactMessage) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">الحالة:</label>
                        <select name="status" class="form-select" required>
                            <option value="new" @selected($contactMessage->status === 'new')>جديد</option>
                            <option value="read" @selected($contactMessage->status === 'read')>مقروء</option>
                            <option value="replied" @selected($contactMessage->status === 'replied')>تم الرد</option>
                            <option value="closed" @selected($contactMessage->status === 'closed')>مغلق</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ملاحظات الإدارة:</label>
                        <textarea name="admin_notes" class="form-control" rows="4" 
                                  placeholder="أضف ملاحظات حول هذه الرسالة...">{{ $contactMessage->admin_notes }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="message-meta">
                <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>إجراءات سريعة</h5>
                
                <div class="action-buttons">
                    @if($contactMessage->status === 'new')
                        <button onclick="markAsRead({{ $contactMessage->id }})" 
                                class="btn btn-warning">
                            <i class="fas fa-eye me-2"></i>تمييز كمقروء
                        </button>
                    @endif
                    
                    @if($contactMessage->status !== 'replied')
                        <button onclick="markAsReplied({{ $contactMessage->id }})" 
                                class="btn btn-success">
                            <i class="fas fa-reply me-2"></i>تمييز كرد
                        </button>
                    @endif
                    
                    @if($contactMessage->status !== 'closed')
                        <button onclick="markAsClosed({{ $contactMessage->id }})" 
                                class="btn btn-secondary">
                            <i class="fas fa-lock me-2"></i>إغلاق
                        </button>
                    @endif
                    
                    <button onclick="deleteMessage({{ $contactMessage->id }})" 
                            class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
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
</script>
@endsection
