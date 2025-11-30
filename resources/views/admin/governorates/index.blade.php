@extends('admin.layouts.app')

@section('title', 'المحافظات')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $governorates->total() }}</div>
                        <div class="stats-label">إجمالي المحافظات</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-map"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $governorates->where('is_active', true)->count() }}</div>
                        <div class="stats-label">محافظات نشطة</div>
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
                        <div class="stats-number">{{ $governorates->sum('cities_count') }}</div>
                        <div class="stats-label">إجمالي المدن</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-city"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $governorates->sum('vendors_count') }}</div>
                        <div class="stats-label">إجمالي الموردين</div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-store"></i>
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
                            <h3>المحافظات</h3>
                            <div class="stats-summary">
                                <div class="stat-item">
                                    <i class="fas fa-map"></i>
                                    <span>{{ $governorates->total() }} محافظة</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $governorates->where('is_active', true)->count() }} نشط</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-city"></i>
                                    <span>{{ $governorates->sum('cities_count') }} مدينة</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.governorates.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة محافظة جديدة
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
                                    <th>الاسم (عربي)</th>
                                    <th>الاسم (إنجليزي)</th>
                                    <th>الكود</th>
                                    <th>المدن</th>
                                    <th>المنتجات</th>
                                    <th>الموردون</th>
                                    <th>الحالة</th>
                                    <th>ترتيب</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($governorates as $governorate)
                                <tr>
                                    <td>{{ $governorate->id }}</td>
                                    <td>{{ $governorate->name_ar }}</td>
                                    <td>{{ $governorate->name_en }}</td>
                                    <td><code>{{ $governorate->code }}</code></td>
                                    <td>
                                        <span class="badge badge-info">{{ $governorate->cities_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $governorate->products_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ $governorate->vendors_count }}</span>
                                    </td>
                                    <td>
                                        @if($governorate->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $governorate->sort_order }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.governorates.show', $governorate) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.governorates.edit', $governorate) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.governorates.destroy', $governorate) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحافظة؟')">
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
                                    <td colspan="10" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-map"></i>
                                            <h4>لا توجد محافظات</h4>
                                            <p>لم يتم إضافة أي محافظات بعد</p>
                                            <a href="{{ route('admin.governorates.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إضافة أول محافظة
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($governorates->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="تعدد صفحات المحافظات">
                                {{ $governorates->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                        <div class="pagination-info">
                            عرض <strong>{{ $governorates->firstItem() }}</strong> إلى <strong>{{ $governorates->lastItem() }}</strong> من أصل <strong>{{ $governorates->total() }}</strong> محافظة
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
