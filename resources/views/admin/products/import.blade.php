@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-import me-2"></i>استيراد المنتجات</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للمنتجات
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>رفع ملف المنتجات</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">اختر ملف CSV أو Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">الحد الأقصى لحجم الملف: 10 ميجابايت. يمكنك استخدام ملف Excel (.xls, .xlsx) أو CSV</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.products.download-template') }}" class="btn btn-outline-primary me-md-2" download>
                            <i class="fas fa-download me-2"></i>تحميل القالب
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>استيراد المنتجات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>تعليمات الاستيراد</h5>
            </div>
            <div class="card-body">
                <h6>متطلبات الملف:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>يجب أن يكون ملف CSV أو Excel</li>
                    <li><i class="fas fa-check text-success me-2"></i>يجب أن يحتوي على رؤوس الأعمدة</li>
                    <li><i class="fas fa-check text-success me-2"></i>الحقول المطلوبة: الاسم، السعر، المخزون، كود المنتج</li>
                </ul>
                
                <h6 class="mt-3">الحقول المطلوبة:</h6>
                <ul class="list-unstyled small">
                    <li><strong>name:</strong> اسم المنتج</li>
                    <li><strong>price:</strong> السعر</li>
                    <li><strong>stock:</strong> المخزون</li>
                    <li><strong>sku:</strong> كود المنتج (فريد)</li>
                    <li><strong>vendor_id:</strong> رقم المورد</li>
                    <li><strong>origin:</strong> المنشأ</li>
                    <li><strong>unit:</strong> الوحدة</li>
                </ul>
                
                <h6 class="mt-3">الح قول الاختيارية:</h6>
                <ul class="list-unstyled small">
                    <li><strong>description:</strong> الوصف</li>
                    <li><strong>original_price:</strong> السعر الأصلي</li>
                    <li><strong>barcode:</strong> الباركود</li>
                    <li><strong>category_id:</strong> رقم القسم الرئيسي <span class="badge bg-info">أو اسم القسم</span></li>
                    <li><strong>subcategory_id:</strong> رقم القسم الفرعي <span class="badge bg-info">أو اسم القسم الفرعي</span></li>
                    <li><strong>brand_id:</strong> رقم الماركة <span class="badge bg-info">أو اسم الماركة</span></li>
                    <li><strong>governorate_id:</strong> رقم المحافظة</li>
                    <li><strong>city_id:</strong> رقم المدينة</li>
                    <li><strong>weight:</strong> الوزن</li>
                    <li><strong>is_fresh:</strong> طازج؟ <span class="badge bg-warning">1 أو 0 أو true أو false</span></li>
                    <li><strong>is_featured:</strong> مميز؟ <span class="badge bg-warning">1 أو 0 أو true أو false</span></li>
                    <li><strong>is_active:</strong> نشط؟ <span class="badge bg-warning">1 أو 0 أو true أو false</span></li>
                    <li><strong>status:</strong> حالة المنتج <span class="badge bg-success">pending أو approved أو rejected</span></li>
                    <li><strong>rating:</strong> التقييم</li>
                    <li><strong>reviews_count:</strong> عدد المراجعات</li>
                    <li><strong>sales_count:</strong> عدد المبيعات</li>
                    <li><strong>nutritional_info:</strong> المعلومات الغذائية</li>
                    <li><strong>expiry_date:</strong> تاريخ الانتهاء</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة مهمة:</strong> يمكنك إدخال اسم القسم أو الماركة كنص، وسيتم إنشاؤها تلقائياً إذا لم تكن موجودة.
                </div>
                
                <div class="alert alert-success mt-2">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>القيم الافتراضية للحقول الفارغة:</strong>
                    <ul class="mb-0 mt-2 small">
                        <li><strong>stock:</strong> 0</li>
                        <li><strong>vendor_id:</strong> 1</li>
                        <li><strong>origin:</strong> الكويت</li>
                        <li><strong>unit:</strong> كيلو</li>
                        <li><strong>status:</strong> pending</li>
                        <li><strong>is_active:</strong> true (1)</li>
                        <li><strong>is_fresh:</strong> false (0)</li>
                        <li><strong>is_featured:</strong> false (0)</li>
                        <li><strong>rating:</strong> 0.0</li>
                        <li><strong>reviews_count:</strong> 0</li>
                        <li><strong>sales_count:</strong> 0</li>
                        <li><strong>subcategory_id:</strong> null</li>
                        <li><strong>brand_id:</strong> null</li>
                        <li><strong>governorate_id:</strong> null</li>
                        <li><strong>city_id:</strong> null</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.list-unstyled li {
    margin-bottom: 0.5rem;
}
</style>
@endsection
