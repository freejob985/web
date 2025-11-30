@extends('admin.layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">الأسئلة الشائعة</h3>
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة سؤال جديد
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($faqs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>السؤال</th>
                                        <th>الفئة</th>
                                        <th>ترتيب</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faqs as $faq)
                                        <tr>
                                            <td>{{ $faq->id }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $faq->question_ar }}">
                                                    {{ $faq->question_ar }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    @switch($faq->category)
                                                        @case('orders') الطلبات @break
                                                        @case('payment') الدفع @break
                                                        @case('delivery') التوصيل @break
                                                        @case('products') المنتجات @break
                                                        @case('support') الدعم @break
                                                        @default عام
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ $faq->sort_order }}</td>
                                            <td>
                                                @if($faq->is_active)
                                                    <span class="badge badge-success">نشط</span>
                                                @else
                                                    <span class="badge badge-secondary">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>{{ $faq->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5>لا توجد أسئلة شائعة</h5>
                            <p class="text-muted">ابدأ بإضافة أول سؤال شائع</p>
                            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة سؤال جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
