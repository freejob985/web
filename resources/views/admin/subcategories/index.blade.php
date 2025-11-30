@extends('admin.layouts.app')

@section('title', 'الأقسام الفرعية')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $subcategories->total() }}</div>
                        <div class="stats-label">إجمالي الأقسام الفرعية</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $subcategories->where('is_active', true)->count() }}</div>
                        <div class="stats-label">أقسام فرعية نشطة</div>
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
                        <div class="stats-number">{{ $subcategories->groupBy('category_id')->count() }}</div>
                        <div class="stats-label">أقسام رئيسية تحتوي على أقسام فرعية</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $subcategories->sum('products_count') }}</div>
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
                            <h3>الأقسام الفرعية</h3>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <i class="fas fa-tags"></i>
                                    <span>{{ $subcategories->total() }} قسم فرعي</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $subcategories->where('is_active', true)->count() }} نشط</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-layer-group"></i>
                                    <span>{{ $subcategories->groupBy('category_id')->count() }} قسم رئيسي</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة قسم فرعي جديد
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
                                    <th>القسم الرئيسي</th>
                                    <th>المنتجات</th>
                                    <th>الحالة</th>
                                    <th>ترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $subcategory)
                                <tr>
                                    <td>{{ $subcategory->id }}</td>
                                    <td>
                                        @if($subcategory->image)
                                            <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                                        @else
                                            <span class="text-muted">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>{{ $subcategory->name_ar }}</td>
                                    <td>{{ $subcategory->name_en }}</td>
                                    <td>{{ $subcategory->category->name_ar }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ $subcategory->products_count }}</span>
                                    </td>
                                    <td>
                                        @if($subcategory->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $subcategory->sort_order }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.subcategories.show', $subcategory) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم الفرعي؟')">
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
                                            <i class="fas fa-tags"></i>
                                            <h4>لا توجد أقسام فرعية</h4>
                                            <p>لم يتم إضافة أي أقسام فرعية بعد</p>
                                            <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إضافة أول قسم فرعي
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($subcategories->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="تعدد صفحات الأقسام الفرعية">
                                {{ $subcategories->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                        <div class="pagination-info">
                            عرض <strong>{{ $subcategories->firstItem() }}</strong> إلى <strong>{{ $subcategories->lastItem() }}</strong> من أصل <strong>{{ $subcategories->total() }}</strong> قسم فرعي
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
