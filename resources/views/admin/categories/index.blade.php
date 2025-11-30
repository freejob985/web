@extends('admin.layouts.app')

@section('title', 'الأقسام الرئيسية')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $categories->total() }}</div>
                        <div class="stats-label">إجمالي الأقسام</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $categories->where('is_active', true)->count() }}</div>
                        <div class="stats-label">أقسام نشطة</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $categories->sum('subcategories_count') }}</div>
                        <div class="stats-label">إجمالي الأقسام الفرعية</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $categories->sum('products_count') }}</div>
                        <div class="stats-label">إجمالي المنتجات</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-header-stats">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3>الأقسام الرئيسية</h3>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <i class="fas fa-layer-group"></i>
                                    <span>{{ $categories->total() }} قسم</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $categories->where('is_active', true)->count() }} نشط</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-tags"></i>
                                    <span>{{ $categories->sum('subcategories_count') }} قسم فرعي</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة قسم جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>الاسم (عربي)</th>
                                    <th>الاسم (إنجليزي)</th>
                                    <th>الأقسام الفرعية</th>
                                    <th>المنتجات</th>
                                    <th>الحالة</th>
                                    <th>ترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                        @else
                                            <span class="text-muted">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>{{ $category->name_ar }}</td>
                                    <td>{{ $category->name_en }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $category->subcategories_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $category->products_count }}</span>
                                    </td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $category->sort_order }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-layer-group"></i>
                                            <h4>لا توجد أقسام</h4>
                                            <p>لم يتم إضافة أي أقسام رئيسية بعد</p>
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إضافة أول قسم
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="تعدد صفحات الأقسام">
                                {{ $categories->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                        <div class="pagination-info">
                            عرض <strong>{{ $categories->firstItem() }}</strong> إلى <strong>{{ $categories->lastItem() }}</strong> من أصل <strong>{{ $categories->total() }}</strong> قسم
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
