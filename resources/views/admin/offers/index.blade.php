@extends('admin.layouts.app')

@section('title', 'العروض')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 font-weight-bold">
                                <i class="fas fa-tags me-3"></i>إدارة العروض
                            </h1>
                            <p class="mb-0 opacity-75">إدارة وتنظيم جميع العروض والخصومات الخاصة بالمتجر</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.offers.create') }}" class="btn btn-light btn-lg shadow">
                                <i class="fas fa-plus me-2"></i>إضافة عرض جديد
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 font-weight-bold">{{ $offers->total() }}</h4>
                            <p class="mb-0 opacity-75">إجمالي العروض</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $activeOffers = $offers->where('is_active', true)->count();
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $activeOffers }}</h4>
                            <p class="mb-0 opacity-75">العروض النشطة</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $featuredOffers = $offers->where('is_featured', true)->count();
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $featuredOffers }}</h4>
                            <p class="mb-0 opacity-75">العروض المميزة</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $flashSaleOffers = $offers->where('is_limited_time', true)->count();
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $flashSaleOffers }}</h4>
                            <p class="mb-0 opacity-75">بيع سريع</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-bolt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-dark">
                                <i class="fas fa-list-alt me-2 text-primary"></i>
                                قائمة العروض
                            </h5>
                            <p class="mb-0 text-muted small">إدارة وتعديل جميع العروض المتاحة</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="filterOffers('all')">
                                <i class="fas fa-list me-1"></i>الكل
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="filterOffers('active')">
                                <i class="fas fa-check me-1"></i>النشط
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="filterOffers('featured')">
                                <i class="fas fa-star me-1"></i>المميز
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($offers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>الصورة</th>
                                        <th>العنوان</th>
                                        <th>القسم</th>
                                        <th>السعر الأصلي</th>
                                        <th>سعر العرض</th>
                                        <th>الخصم</th>
                                        <th>الحالة</th>
                                        <th>مميز</th>
                                        <th>بيع سريع</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td>{{ $offer->id }}</td>
                                            <td>
                                                @if($offer->image)
                                                    <img src="{{ asset('storage/' . $offer->image) }}" 
                                                         alt="{{ $offer->title }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($offer->title, 30) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($offer->description, 40) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $offer->category->color ?? 'bg-secondary' }} px-2 py-1">
                                                    {{ $offer->category->name ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($offer->original_price, 2) }} د.ك</td>
                                            <td>{{ number_format($offer->offer_price, 2) }} د.ك</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $offer->discount_percentage }}%</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.offers.toggle-status', $offer) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $offer->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        <i class="fas {{ $offer->is_active ? 'fa-check' : 'fa-times' }}"></i>
                                                        {{ $offer->is_active ? 'نشط' : 'غير نشط' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.offers.toggle-popular', $offer) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $offer->is_featured ? 'btn-warning' : 'btn-outline-warning' }}">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.offers.toggle-flash-sale', $offer) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $offer->is_limited_time ? 'btn-danger' : 'btn-outline-danger' }}">
                                                        <i class="fas fa-bolt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $offer->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.offers.show', $offer) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.offers.edit', $offer) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.offers.destroy', $offer) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer border-0 bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="text-muted d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>
                                        عرض <strong>{{ $offers->firstItem() }}</strong> إلى <strong>{{ $offers->lastItem() }}</strong> من إجمالي <strong>{{ $offers->total() }}</strong> عرض
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $offers->links('admin.partials.pagination') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد عروض</h5>
                            <p class="text-muted">ابدأ بإنشاء عرض جديد</p>
                            <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                إضافة عرض جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function filterOffers(type) {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let show = true;
        
        if (type === 'active') {
            const statusBtn = row.querySelector('form[action*="toggle-status"] button');
            show = statusBtn && statusBtn.classList.contains('btn-success');
        } else if (type === 'featured') {
            const featuredBtn = row.querySelector('form[action*="toggle-popular"] button');
            show = featuredBtn && !featuredBtn.classList.contains('btn-outline-warning');
        }
        
        row.style.display = show ? '' : 'none';
    });
    
    // Update active button
    document.querySelectorAll('.btn-outline-primary, .btn-outline-success, .btn-outline-warning').forEach(btn => {
        btn.classList.remove('btn-primary', 'btn-success', 'btn-warning');
        btn.classList.add('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning');
    });
    
    event.target.classList.remove('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning');
    event.target.classList.add('btn-primary');
}

// Add loading animation to form submissions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري المعالجة...';
                submitBtn.disabled = true;
            }
        });
    });
});
</script><style>
/* Enhanced spacing and layout */
.container-fluid {
    padding: 20px;
}

.row {
    margin-bottom: 20px;
}

.card {
    margin-bottom: 25px;
}

.card-body {
    padding: 25px;
}

.table-responsive {
    margin: 20px 0;
}

.btn-group .btn {
    margin: 0 3px;
}

.badge {
    margin: 2px;
}

/* Better component spacing */
.component-spacing > * {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.alert {
    margin-bottom: 20px;
}

/* Enhanced table styling */
.table td {
    padding: 15px 12px;
    vertical-align: middle;
}

.table th {
    padding: 18px 12px;
}

/* Better button spacing */
.d-flex.gap-2 > * {
    margin-left: 8px;
}

.d-flex.gap-3 > * {
    margin-left: 12px;
}

/* Card header improvements */
.card-header {
    padding: 20px 25px;
}

/* Statistics cards spacing */
.col-xl-3, .col-md-6 {
    padding: 0 15px;
}

.mb-4 {
    margin-bottom: 2rem !important;
}

.mb-3 {
    margin-bottom: 1.5rem !important;
}
</style>