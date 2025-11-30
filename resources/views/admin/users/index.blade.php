@extends('admin.layouts.app')

@section('content')
<!-- Enhanced Statistics Cards -->
<div class="row mb-5">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="stats-card">
            <div class="stats-card-body">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-users stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $users->total() }}</div>
                    <div class="stats-label">إجمالي المستخدمين</div>
                </div>
                <div class="stats-footer">
                    <div class="stats-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8.2% من الشهر الماضي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="stats-card active-users">
            <div class="stats-card-body">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-user-check stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $users->where('is_active', true)->count() }}</div>
                    <div class="stats-label">المستخدمين النشطين</div>
                </div>
                <div class="stats-footer">
                    <div class="stats-trend positive">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ number_format(($users->where('is_active', true)->count() / $users->total()) * 100, 1) }}% من الإجمالي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="stats-card inactive-users">
            <div class="stats-card-body">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-user-times stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $users->where('is_active', false)->count() }}</div>
                    <div class="stats-label">المستخدمين غير النشطين</div>
                </div>
                <div class="stats-footer">
                    <div class="stats-trend neutral">
                        <i class="fas fa-pause-circle"></i>
                        <span>يحتاجون مراجعة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="stats-card new-users">
            <div class="stats-card-body">
                <div class="stats-icon-wrapper">
                    <i class="fas fa-user-plus stats-icon"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-number">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                    <div class="stats-label">مستخدمين جدد هذا الشهر</div>
                </div>
                <div class="stats-footer">
                    <div class="stats-trend positive">
                        <i class="fas fa-calendar-plus"></i>
                        <span>نمو مستمر</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users me-2"></i>إدارة المستخدمين</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلترة المستخدمين</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <input type="text" id="searchInput" class="form-control" placeholder="البحث في المستخدمين...">
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select id="statusFilter" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">تاريخ التسجيل</label>
                <select id="dateFilter" class="form-select">
                    <option value="">جميع التواريخ</option>
                    <option value="today">اليوم</option>
                    <option value="week">هذا الأسبوع</option>
                    <option value="month">هذا الشهر</option>
                    <option value="year">هذا العام</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button id="clearFilters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i>مسح
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="usersTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th>آخر نشاط</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-muted me-2"></i>
                                {{ $user->phone ?? 'غير محدد' }}
                            </div>
                        </td>
                        <td>
                            @if($user->is_active ?? true)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>نشط
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>غير نشط
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $user->created_at->format('Y-m-d') }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $user->updated_at->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(isset($user->is_active))
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleUserStatus({{ $user->id }}, {{ ($user->is_active ?? true) ? 'false' : 'true' }})">
                                        <i class="fas fa-{{ ($user->is_active ?? true) ? 'pause' : 'play' }}"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="sendMessage({{ $user->id }})">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
/* تحسين تنسيق الهيدر */
.table-dark th {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    text-align: center;
    padding: 15px 10px;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

.table-dark th:first-child {
    border-top-right-radius: 10px;
}

.table-dark th:last-child {
    border-top-left-radius: 10px;
}

/* تحسين تنسيق الأزرار */
.dt-buttons {
    margin-bottom: 20px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-start;
    direction: ltr;
}

.dt-buttons .btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    min-width: 120px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dt-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.dt-buttons .btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
}

.dt-buttons .btn-danger {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    border: none;
    color: white;
}

.dt-buttons .btn-info {
    background: linear-gradient(135deg, #17a2b8, #6f42c1);
    border: none;
    color: white;
}

/* تحسين تنسيق الجدول */
.table {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 0;
}

.table tbody td {
    padding: 18px 15px;
    vertical-align: middle;
    border-color: #f1f3f4;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(52, 152, 219, 0.05);
}

.table-hover tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.1) !important;
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* Enhanced Row Spacing */
.table tbody tr {
    border-bottom: 2px solid #f8f9fa;
}

.table tbody tr:last-child {
    border-bottom: none;
}

/* تحسين تنسيق البطاقات */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

/* تحسين تنسيق الفلاتر */
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

/* Enhanced Statistics Cards */
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 0;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    transition: all 0.4s ease;
    overflow: hidden;
    position: relative;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.stats-card.active-users {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.stats-card.inactive-users {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.stats-card.new-users {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.stats-card-body {
    padding: 25px;
    position: relative;
    z-index: 2;
}

.stats-icon-wrapper {
    text-align: center;
    margin-bottom: 20px;
}

.stats-icon {
    font-size: 3rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.stats-content {
    text-align: center;
    margin-bottom: 20px;
}

.stats-number {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 10px 0;
    line-height: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0;
}

.stats-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 15px;
    text-align: center;
}

.stats-trend {
    font-size: 0.8rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.stats-trend.positive {
    color: rgba(255, 255, 255, 0.9);
}

.stats-trend.neutral {
    color: rgba(255, 255, 255, 0.8);
}

.stats-trend i {
    font-size: 0.9rem;
}

/* تحسين تنسيق الإحصائيات القديمة */
.card-body h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0;
}

.card-body h5 {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 10px;
}

/* تحسين تنسيق الشارات */
.badge {
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
}

/* تحسين تنسيق أزرار الإجراءات */
.btn-group {
    display: flex;
    gap: 4px;
    justify-content: flex-start;
}

.btn-group .btn {
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 0.7rem;
    transition: all 0.3s ease;
    min-width: 32px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.btn-group .btn-outline-primary {
    border-color: #3498db;
    color: #3498db;
}

.btn-group .btn-outline-primary:hover {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-group .btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

.btn-group .btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-group .btn-outline-warning {
    border-color: #ffc107;
    color: #ffc107;
}

.btn-group .btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
}

/* تحسين تنسيق الصور الرمزية */
.avatar-sm {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}
</style>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
        },
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-2"></i>تصدير Excel',
                className: 'btn btn-success btn-sm',
                title: 'قائمة المستخدمين',
                filename: 'users_' + new Date().toISOString().split('T')[0]
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-2"></i>طباعة',
                className: 'btn btn-info btn-sm',
                title: 'قائمة المستخدمين'
            }
        ],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ]
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        if (this.value === 'active') {
            table.column(4).search('نشط').draw();
        } else if (this.value === 'inactive') {
            table.column(4).search('غير نشط').draw();
        } else {
            table.column(4).search('').draw();
        }
    });

    // Date filter
    $('#dateFilter').on('change', function() {
        const today = new Date();
        let filterDate = '';
        
        switch(this.value) {
            case 'today':
                filterDate = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                filterDate = weekAgo.toISOString().split('T')[0];
                break;
            case 'month':
                const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                filterDate = monthAgo.toISOString().split('T')[0];
                break;
            case 'year':
                const yearAgo = new Date(today.getTime() - 365 * 24 * 60 * 60 * 1000);
                filterDate = yearAgo.toISOString().split('T')[0];
                break;
        }
        
        if (filterDate) {
            table.column(5).search(filterDate).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#dateFilter').val('');
        table.search('').columns().search('').draw();
    });
});

function toggleUserStatus(userId, newStatus) {
    const action = newStatus === 'true' ? 'تفعيل' : 'إلغاء تفعيل';
    
    Swal.fire({
        title: `${action} المستخدم`,
        text: `هل تريد ${action.toLowerCase()} هذا المستخدم؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `نعم، ${action}`,
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/toggle-status`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const statusField = document.createElement('input');
            statusField.type = 'hidden';
            statusField.name = 'is_active';
            statusField.value = newStatus;
            
            form.appendChild(csrfToken);
            form.appendChild(statusField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function sendMessage(userId) {
    Swal.fire({
        title: 'إرسال رسالة',
        text: 'هل تريد إرسال رسالة إلى هذا المستخدم؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، إرسال',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to message page or open modal
            window.location.href = `/admin/users/${userId}/message`;
        }
    });
}
</script>
@endsection
