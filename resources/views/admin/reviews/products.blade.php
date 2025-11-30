@extends('admin.layouts.app')

@section('title', 'إدارة تقييمات المنتجات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تقييمات المنتجات</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reviews.stats') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <select name="status" class="form-control me-2" onchange="this.form.submit()">
                                    <option value="">جميع الحالات</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <select name="rating" class="form-control me-2" onchange="this.form.submit()">
                                    <option value="">جميع التقييمات</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 نجوم</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 نجوم</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 نجوم</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 نجوم</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 نجمة</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="البحث..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Reviews Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>المنتج</th>
                                    <th>التقييم</th>
                                    <th>التعليق</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($review->user->name ?? 'م', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $review->display_name }}</div>
                                                <small class="text-muted">{{ $review->user->email ?? 'غير محدد' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $review->product->name }}</div>
                                            <small class="text-muted">بواسطة: {{ $review->vendor->name }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $review->comment }}">
                                            {{ $review->comment ?? 'لا يوجد تعليق' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">معتمد</span>
                                        @else
                                            <span class="badge bg-warning">في الانتظار</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $review->formatted_date }}</div>
                                        @if($review->is_anonymous)
                                            <small class="text-muted">مجهول</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(!$review->is_approved)
                                                <form method="POST" action="{{ route('admin.reviews.products.approve', $review->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="موافقة">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($review->is_approved)
                                                <form method="POST" action="{{ route('admin.reviews.products.reject', $review->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm" title="رفض">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form method="POST" action="{{ route('admin.reviews.products.delete', $review->id) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد تقييمات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
}
</style>
@endpush
