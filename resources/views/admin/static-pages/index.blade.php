@extends('admin.layouts.app')

@section('title', 'إدارة الصفحات الثابتة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة الصفحات الثابتة</h3>
                    <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة صفحة جديدة
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>الرابط</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>الترتيب</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>{{ $page->id }}</td>
                                        <td>{{ $page->title }}</td>
                                        <td>
                                            <code>{{ $page->slug }}</code>
                                        </td>
                                        <td>
                                            @if($page->is_fixed)
                                                <span class="badge badge-warning">ثابتة</span>
                                            @else
                                                <span class="badge badge-info">قابلة للتعديل</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($page->is_active)
                                                <span class="badge badge-success">نشطة</span>
                                            @else
                                                <span class="badge badge-secondary">غير نشطة</span>
                                            @endif
                                        </td>
                                        <td>{{ $page->sort_order }}</td>
                                        <td>{{ $page->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.static-pages.show', $page) }}" 
                                                   class="btn btn-sm btn-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if(!$page->is_fixed)
                                                    <a href="{{ route('admin.static-pages.edit', $page) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('admin.static-pages.toggle-status', $page) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm {{ $page->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                                title="{{ $page->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                            <i class="fas {{ $page->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('admin.static-pages.destroy', $page) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الصفحة؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">غير قابل للتعديل</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد صفحات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
