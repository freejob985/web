@extends('admin.layouts.app')

@section('title', 'أقسام العروض')

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
                                <i class="fas fa-layer-group me-3"></i>أقسام العروض
                            </h1>
                            <p class="mb-0 opacity-75">إدارة وتنظيم أقسام العروض والخصومات</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.offer-categories.create') }}" class="btn btn-light btn-lg shadow">
                                <i class="fas fa-plus me-2"></i>إضافة قسم جديد
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
                            <h4 class="mb-1 font-weight-bold">{{ $categories->total() }}</h4>
                            <p class="mb-0 opacity-75">إجمالي الأقسام</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-layer-group fa-2x"></i>
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
                                $activeCategories = $categories->where('is_active', true)->count();
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $activeCategories }}</h4>
                            <p class="mb-0 opacity-75">الأقسام النشطة</p>
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
                                $totalOffers = $categories->sum('offers_count') ?? 0;
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $totalOffers }}</h4>
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
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $avgOffers = $categories->count() > 0 ? round($totalOffers / $categories->count(), 1) : 0;
                            @endphp
                            <h4 class="mb-1 font-weight-bold">{{ $avgOffers }}</h4>
                            <p class="mb-0 opacity-75">متوسط العروض</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x"></i>
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
                                قائمة أقسام العروض
                            </h5>
                            <p class="mb-0 text-muted small">إدارة وتنظيم أقسام العروض المختلفة</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="sortCategories('name')">
                                <i class="fas fa-sort-alpha-down me-1"></i>ترتيب أبجدي
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="sortCategories('offers')">
                                <i class="fas fa-sort-numeric-down me-1"></i>حسب العروض
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

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>الصورة</th>
                                        <th>الاسم</th>
                                        <th>الوصف</th>
                                        <th>اللون</th>
                                        <th>الحالة</th>
                                        <th>ترتيب</th>
                                        <th>عدد العروض</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                                         alt="{{ $category->name }}" 
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
                                                <strong>{{ $category->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $category->slug }}</small>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="{{ $category->description }}">
                                                    {{ Str::limit($category->description, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $category->color }} px-3 py-2">
                                                    {{ $category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.offer-categories.toggle-status', $category) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $category->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        <i class="fas {{ $category->is_active ? 'fa-check' : 'fa-times' }}"></i>
                                                        {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $category->sort_order }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->offers_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.offer-categories.show', $category) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.offer-categories.edit', $category) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.offer-categories.destroy', $category) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
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
                                        عرض <strong>{{ $categories->firstItem() }}</strong> إلى <strong>{{ $categories->lastItem() }}</strong> من إجمالي <strong>{{ $categories->total() }}</strong> قسم
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $categories->links('admin.partials.pagination') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد أقسام عروض</h5>
                            <p class="text-muted">ابدأ بإنشاء قسم جديد للعروض</p>
                            <a href="{{ route('admin.offer-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                إضافة قسم جديد
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
function sortCategories(type) {
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        if (type === 'name') {
            const nameA = a.cells[2].textContent.trim().toLowerCase();
            const nameB = b.cells[2].textContent.trim().toLowerCase();
            return nameA.localeCompare(nameB);
        } else if (type === 'offers') {
            const offersA = parseInt(a.cells[7].textContent.trim());
            const offersB = parseInt(b.cells[7].textContent.trim());
            return offersB - offersA;
        }
        return 0;
    });
    
    // Clear tbody and append sorted rows
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
    
    // Update active button
    document.querySelectorAll('.btn-outline-primary, .btn-outline-success').forEach(btn => {
        btn.classList.remove('btn-primary', 'btn-success');
        btn.classList.add(btn.textContent.includes('أبجدي') ? 'btn-outline-primary' : 'btn-outline-success');
    });
    
    event.target.classList.remove('btn-outline-primary', 'btn-outline-success');
    event.target.classList.add('btn-primary');
}

// Add hover effects to category cards
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(-5px)';
            this.style.boxShadow = '0 4px 15px rgba(52, 152, 219, 0.15)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>