@extends('admin.layouts.app')

@section('title', 'إدارة طرق التواصل')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">طرق التواصل</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contact-methods.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة طريقة تواصل جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الأيقونة</th>
                                <th>القيمة</th>
                                <th>الرابط</th>
                                <th>الحالة</th>
                                <th>الترتيب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contactMethods as $index => $method)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $method->name }}</td>
                                    <td><i class="{{ $method->icon }}"></i> {{ $method->icon }}</td>
                                    <td>{{ $method->value }}</td>
                                    <td>{{ $method->link }}</td>
                                    <td>
                                        @if($method->is_active)
                                            <span class="badge badge-success">مفعل</span>
                                        @else
                                            <span class="badge badge-danger">غير مفعل</span>
                                        @endif
                                    </td>
                                    <td>{{ $method->sort_order }}</td>
                                    <td>
                                        <a href="{{ route('admin.contact-methods.edit', $method->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.contact-methods.destroy', $method->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذه الطريقة؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد طرق تواصل مضافة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection