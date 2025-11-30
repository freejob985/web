@extends('admin.layouts.app')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-box text-primary" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">إجمالي المنتجات</h5>
                <h3 class="text-primary">{{ $products->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle text-success" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">المنتجات النشطة</h5>
                <h3 class="text-success">{{ $products->where('is_active', true)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-times-circle text-danger" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">المنتجات غير النشطة</h5>
                <h3 class="text-danger">{{ $products->where('is_active', false)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="card-title">نفاد المخزون</h5>
                <h3 class="text-warning">{{ $products->where('stock', 0)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-box me-2"></i>إدارة المنتجات</h1>
    <div>
        <a href="{{ route('admin.products.import') }}" class="btn btn-success me-2">
            <i class="fas fa-file-import me-2"></i>استيراد منتجات
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة منتج
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلترة المنتجات</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <input type="text" id="searchInput" class="form-control" placeholder="البحث في المنتجات...">
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
                <label class="form-label">المخزون</label>
                <select id="stockFilter" class="form-select">
                    <option value="">جميع المستويات</option>
                    <option value="in-stock">متوفر</option>
                    <option value="low-stock">مخزون منخفض</option>
                    <option value="out-of-stock">نفد المخزون</option>
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
        <!-- Pagination Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <label class="form-label me-2">عرض:</label>
                <select id="perPageSelect" class="form-select d-inline-block w-auto" onchange="changePerPage(this.value)">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                </select>
                <span class="ms-2">عنصر لكل صفحة</span>
            </div>
            <div>
                <strong>إجمالي المنتجات: {{ $products->total() }}</strong>
            </div>
        </div>

        <div class="table-responsive">
            <table id="productsTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الحالة</th>
                        <th>المورد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}" class="product-thumbnail" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                            @else
                                <div class="no-image-placeholder" style="width: 50px; height: 50px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-box text-muted me-2"></i>
                                {{ $product->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ number_format($product->price, 3) }} د.ك</span>
                        </td>
                        <td>
                            @if($product->stock == 0)
                                <span class="badge bg-danger">نفد المخزون</span>
                            @elseif($product->stock < 10)
                                <span class="badge bg-warning">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
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
                            <i class="fas fa-store text-muted me-1"></i>
                            {{ optional($product->vendor)->name ?? 'غير محدد' }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProduct({{ $product->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        <!-- Laravel Pagination with Custom Style -->
        <div class="mt-4">
            {{ $products->appends(['per_page' => request('per_page', 20)])->links('vendor.pagination.custom') }}
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

/* تحسين عرض صور المنتجات */
.product-thumbnail {
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-thumbnail:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.no-image-placeholder {
    transition: all 0.3s ease;
}

.no-image-placeholder:hover {
    background: #e9ecef !important;
    border-color: #adb5bd !important;
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
    var table = $('#productsTable').DataTable({
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
                title: 'قائمة المنتجات',
                filename: 'products_' + new Date().toISOString().split('T')[0]
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-2"></i>طباعة',
                className: 'btn btn-info btn-sm',
                title: 'قائمة المنتجات'
            }
        ],
        "columnDefs": [
            { "orderable": false, "targets": 6 }
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

    // Stock filter
    $('#stockFilter').on('change', function() {
        if (this.value === 'in-stock') {
            table.column(3).search('^(?!.*نفد المخزون).*$', true, false).draw();
        } else if (this.value === 'low-stock') {
            table.column(3).search('^(?!.*نفد المخزون).*[0-9]+.*$', true, false).draw();
        } else if (this.value === 'out-of-stock') {
            table.column(3).search('نفد المخزون').draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#stockFilter').val('');
        table.search('').columns().search('').draw();
    });
});

function deleteProduct(productId) {
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
            form.action = `/admin/products/${productId}`;
            
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

function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}
</script>
@endsection
