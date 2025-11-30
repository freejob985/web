@extends('admin.layouts.app')

@section('content')
<!-- Enhanced Statistics Cards -->
<div class="row mb-5">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="vendor-stats-card total-vendors">
            <div class="vendor-stats-body">
                <div class="vendor-stats-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="vendor-stats-content">
                    <div class="vendor-stats-number">{{ $vendors->total() }}</div>
                    <div class="vendor-stats-label">إجمالي الموردين</div>
                </div>
                <div class="vendor-stats-footer">
                    <div class="vendor-stats-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+15% نمو شهري</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="vendor-stats-card active-vendors">
            <div class="vendor-stats-body">
                <div class="vendor-stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="vendor-stats-content">
                    <div class="vendor-stats-number">{{ $vendors->where('is_active', true)->count() }}</div>
                    <div class="vendor-stats-label">الموردين النشطين</div>
                </div>
                <div class="vendor-stats-footer">
                    <div class="vendor-stats-trend positive">
                        <i class="fas fa-thumbs-up"></i>
                        <span>{{ number_format(($vendors->where('is_active', true)->count() / max($vendors->total(), 1)) * 100, 1) }}% معدل النشاط</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="vendor-stats-card inactive-vendors">
            <div class="vendor-stats-body">
                <div class="vendor-stats-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="vendor-stats-content">
                    <div class="vendor-stats-number">{{ $vendors->where('is_active', false)->count() }}</div>
                    <div class="vendor-stats-label">الموردين غير النشطين</div>
                </div>
                <div class="vendor-stats-footer">
                    <div class="vendor-stats-trend neutral">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>يحتاجون مراجعة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
        <div class="vendor-stats-card premium-vendors">
            <div class="vendor-stats-body">
                <div class="vendor-stats-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="vendor-stats-content">
                    <div class="vendor-stats-number">{{ $vendors->where('status', 'premium')->count() }}</div>
                    <div class="vendor-stats-label">الموردين المتميزين</div>
                </div>
                <div class="vendor-stats-footer">
                    <div class="vendor-stats-trend positive">
                        <i class="fas fa-star"></i>
                        <span>عضوية مميزة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-store me-2"></i>إدارة الموردين</h1>
    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>مورد جديد
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلترة الموردين</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <input type="text" id="searchInput" class="form-control" placeholder="البحث في الموردين...">
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
                <label class="form-label">نوع المورد</label>
                <select id="typeFilter" class="form-select">
                    <option value="">جميع الأنواع</option>
                    <option value="premium">متميز</option>
                    <option value="standard">عادي</option>
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
            <table id="vendorsTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>الحالة</th>
                        <th>النشاط</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-store text-muted me-2"></i>
                                <strong>{{ $vendor->name }}</strong>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                {{ $vendor->email }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-muted me-2"></i>
                                {{ $vendor->phone ?? 'غير محدد' }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'premium' => ['class' => 'success', 'icon' => 'crown', 'text' => 'متميز'],
                                    'standard' => ['class' => 'primary', 'icon' => 'store', 'text' => 'عادي'],
                                    'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'قيد المراجعة'],
                                    'suspended' => ['class' => 'danger', 'icon' => 'ban', 'text' => 'معلق']
                                ];
                                $config = $statusConfig[$vendor->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => $vendor->status];
                            @endphp
                            <span class="badge bg-{{ $config['class'] }}">
                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                            </span>
                        </td>
                        <td>
                            @if($vendor->is_active)
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
                                {{ $vendor->created_at->format('Y-m-d') }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleVendorStatus({{ $vendor->id }}, {{ $vendor->is_active ? 'false' : 'true' }})">
                                    <i class="fas fa-{{ $vendor->is_active ? 'pause' : 'play' }}"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteVendor({{ $vendor->id }})">
                                    <i class="fas fa-trash"></i>
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
/* Enhanced Vendor Statistics Cards */
.vendor-stats-card {
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

.vendor-stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.vendor-stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.vendor-stats-card.total-vendors {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.vendor-stats-card.active-vendors {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.vendor-stats-card.inactive-vendors {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.vendor-stats-card.premium-vendors {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.vendor-stats-body {
    padding: 25px;
    position: relative;
    z-index: 2;
}

.vendor-stats-icon {
    text-align: center;
    margin-bottom: 20px;
}

.vendor-stats-icon i {
    font-size: 3rem;
    opacity: 0.9;
}

.vendor-stats-content {
    text-align: center;
    margin-bottom: 20px;
}

.vendor-stats-number {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 10px 0;
    line-height: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.vendor-stats-label {
    font-size: 0.9rem;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0;
}

.vendor-stats-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 15px;
    text-align: center;
}

.vendor-stats-trend {
    font-size: 0.8rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.vendor-stats-trend.positive {
    color: rgba(255, 255, 255, 0.9);
}

.vendor-stats-trend.neutral {
    color: rgba(255, 255, 255, 0.8);
}

.vendor-stats-trend i {
    font-size: 0.9rem;
}

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
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(52, 152, 219, 0.05);
}

.table-hover tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.1) !important;
    transform: scale(1.01);
    transition: all 0.3s ease;
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

/* تحسين تنسيق الإحصائيات */
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

.btn-group .btn-outline-danger {
    border-color: #e74c3c;
    color: #e74c3c;
}

.btn-group .btn-outline-danger:hover {
    background-color: #e74c3c;
    border-color: #e74c3c;
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
    var table = $('#vendorsTable').DataTable({
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
                title: 'قائمة البائعين',
                filename: 'vendors_' + new Date().toISOString().split('T')[0]
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-2"></i>طباعة',
                className: 'btn btn-info btn-sm',
                title: 'قائمة البائعين'
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
            table.column(5).search('نشط').draw();
        } else if (this.value === 'inactive') {
            table.column(5).search('غير نشط').draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Type filter
    $('#typeFilter').on('change', function() {
        if (this.value === 'premium') {
            table.column(4).search('متميز').draw();
        } else if (this.value === 'standard') {
            table.column(4).search('عادي').draw();
        } else {
            table.column(4).search('').draw();
        }
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        table.search('').columns().search('').draw();
    });
});

function toggleVendorStatus(vendorId, newStatus) {
    const action = newStatus === 'true' ? 'تفعيل' : 'إلغاء تفعيل';
    
    Swal.fire({
        title: `${action} المورد`,
        text: `هل تريد ${action.toLowerCase()} هذا المورد؟`,
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
            form.action = `/admin/vendors/${vendorId}/toggle-status`;
            
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

function deleteVendor(vendorId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لن تتمكن من التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/vendors/${vendorId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
