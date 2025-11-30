@extends('admin.layouts.app')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-shopping-cart text-primary" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">إجمالي الطلبات</h5>
                <h3 class="text-primary">{{ $orders->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock text-warning" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">قيد الانتظار</h5>
                <h3 class="text-warning">{{ $orders->where('status', 'pending')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle text-success" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">تم التسليم</h5>
                <h3 class="text-success">{{ $orders->where('status', 'delivered')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-dollar-sign text-info" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">إجمالي المبيعات</h5>
                <h3 class="text-info">{{ number_format($orders->sum('total_amount'), 3) }} د.ك</h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-shopping-cart me-2"></i>إدارة الطلبات</h1>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلترة الطلبات</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">البحث</label>
                <input type="text" id="searchInput" class="form-control" placeholder="البحث في الطلبات...">
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select id="statusFilter" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="preparing">قيد التحضير</option>
                    <option value="ready">جاهز</option>
                    <option value="shipped">قيد التوصيل</option>
                    <option value="delivered">تم التسليم</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">المتجر</label>
                <select id="vendorFilter" class="form-select">
                    <option value="">جميع المتاجر</option>
                    @foreach($orders->pluck('vendor.name')->filter()->unique() as $vendorName)
                        <option value="{{ $vendorName }}">{{ $vendorName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
            <table id="ordersTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>المتجر</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-receipt text-muted me-2"></i>
                                <strong>{{ $order->order_number }}</strong>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user text-muted me-2"></i>
                                {{ optional($order->user)->name ?? 'غير محدد' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-store text-muted me-2"></i>
                                {{ optional($order->vendor)->name ?? 'غير محدد' }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'قيد الانتظار'],
                                    'confirmed' => ['class' => 'info', 'icon' => 'check', 'text' => 'مؤكد'],
                                    'preparing' => ['class' => 'primary', 'icon' => 'cog', 'text' => 'قيد التحضير'],
                                    'ready' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'جاهز'],
                                    'shipped' => ['class' => 'info', 'icon' => 'truck', 'text' => 'قيد التوصيل'],
                                    'delivered' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'تم التسليم'],
                                    'cancelled' => ['class' => 'danger', 'icon' => 'times', 'text' => 'ملغي']
                                ];
                                $config = $statusConfig[$order->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => $order->status_label];
                            @endphp
                            <span class="badge bg-{{ $config['class'] }}">
                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success fs-6">{{ number_format($order->total_amount, 3) }} د.ك</span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="updateOrderStatus({{ $order->id }}, 'confirmed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                @if(in_array($order->status, ['confirmed', 'preparing']))
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                @endif
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

.btn-group .btn-outline-success {
    border-color: #28a745;
    color: #28a745;
}

.btn-group .btn-outline-success:hover {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-group .btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

.btn-group .btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
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
    var table = $('#ordersTable').DataTable({
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
                title: 'قائمة الطلبات',
                filename: 'orders_' + new Date().toISOString().split('T')[0]
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-2"></i>طباعة',
                className: 'btn btn-info btn-sm',
                title: 'قائمة الطلبات'
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
        if (this.value) {
            table.column(4).search(this.value).draw();
        } else {
            table.column(4).search('').draw();
        }
    });

    // Vendor filter
    $('#vendorFilter').on('change', function() {
        if (this.value) {
            table.column(3).search(this.value).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#vendorFilter').val('');
        table.search('').columns().search('').draw();
    });
});

function updateOrderStatus(orderId, newStatus) {
    const statusTexts = {
        'confirmed': 'مؤكد',
        'shipped': 'قيد التوصيل',
        'delivered': 'تم التسليم'
    };
    
    Swal.fire({
        title: 'تحديث حالة الطلب',
        text: `هل تريد تحديث حالة الطلب إلى "${statusTexts[newStatus]}"؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، تحديث',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/status`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const statusField = document.createElement('input');
            statusField.type = 'hidden';
            statusField.name = 'status';
            statusField.value = newStatus;
            
            form.appendChild(csrfToken);
            form.appendChild(statusField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
