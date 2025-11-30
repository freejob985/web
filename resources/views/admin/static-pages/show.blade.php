@extends('admin.layouts.app')

@section('title', 'عرض الصفحة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عرض الصفحة: {{ $staticPage->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                        @if(!$staticPage->is_fixed)
                            <a href="{{ route('admin.static-pages.edit', $staticPage) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>محتوى الصفحة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="prose max-w-none">
                                        {!! $staticPage->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>معلومات الصفحة</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>المعرف:</strong></td>
                                            <td>{{ $staticPage->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>العنوان:</strong></td>
                                            <td>{{ $staticPage->title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الرابط:</strong></td>
                                            <td><code>{{ $staticPage->slug }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>النوع:</strong></td>
                                            <td>
                                                @if($staticPage->is_fixed)
                                                    <span class="badge badge-warning">ثابتة</span>
                                                @else
                                                    <span class="badge badge-info">قابلة للتعديل</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحالة:</strong></td>
                                            <td>
                                                @if($staticPage->is_active)
                                                    <span class="badge badge-success">نشطة</span>
                                                @else
                                                    <span class="badge badge-secondary">غير نشطة</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الترتيب:</strong></td>
                                            <td>{{ $staticPage->sort_order }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الإنشاء:</strong></td>
                                            <td>{{ $staticPage->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>آخر تحديث:</strong></td>
                                            <td>{{ $staticPage->updated_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($staticPage->meta_description || $staticPage->meta_keywords)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>معلومات SEO</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($staticPage->meta_description)
                                            <div class="mb-3">
                                                <strong>وصف Meta:</strong>
                                                <p class="text-muted">{{ $staticPage->meta_description }}</p>
                                            </div>
                                        @endif
                                        @if($staticPage->meta_keywords)
                                            <div>
                                                <strong>كلمات مفتاحية:</strong>
                                                <p class="text-muted">{{ $staticPage->meta_keywords }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>الإجراءات</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="/{{ $staticPage->slug }}" target="_blank" class="btn btn-info">
                                            <i class="fas fa-external-link-alt"></i> عرض الصفحة
                                        </a>
                                        
                                        @if(!$staticPage->is_fixed)
                                            <a href="{{ route('admin.static-pages.edit', $staticPage) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> تعديل الصفحة
                                            </a>
                                            
                                            <form action="{{ route('admin.static-pages.toggle-status', $staticPage) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn {{ $staticPage->is_active ? 'btn-secondary' : 'btn-success' }} w-100">
                                                    <i class="fas {{ $staticPage->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                    {{ $staticPage->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.static-pages.destroy', $staticPage) }}" method="POST"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصفحة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash"></i> حذف الصفحة
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-info text-center">
                                                <i class="fas fa-lock"></i><br>
                                                صفحة ثابتة<br>
                                                <small>غير قابلة للتعديل</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
