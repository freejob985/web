@extends('admin.layouts.app')

@section('title', 'فئات الأعمال')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فئات الأعمال</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.business-categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة فئة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>الاسم</th>
                                <th>الوصف</th>
                                <th>الأيقونة</th>
                                <th>الترتيب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ Str::limit($category->description, 50) }}</td>
                                <td><i class="fas {{ $category->icon }}"></i></td>
                                <td>{{ $category->sort_order }}</td>
                                <td>
                                    @if($category->is_active)
                                    <span class="badge badge-success">نشط</span>
                                    @else
                                    <span class="badge badge-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.business-categories.edit', $category) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.business-categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد فئات أعمال</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection