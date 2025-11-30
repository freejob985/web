@extends('admin.layouts.app')

@section('title', 'إدارة السلايدرات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة السلايدرات</h3>
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة سلايدر جديد
                    </a>
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

                    @if($sliders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الصورة</th>
                                        <th>العنوان</th>
                                        <th>الوصف</th>
                                        <th>الترتيب</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sliders as $slider)
                                        <tr>
                                            <td>{{ $slider->id }}</td>
                                            <td>
                                                @if($slider->image)
                                                    <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" 
                                                         class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $slider->title }}</strong>
                                                @if($slider->subtitle)
                                                    <br><small class="text-muted">{{ $slider->subtitle }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $slider->description }}">
                                                    {{ $slider->description }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $slider->sort_order }}</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.sliders.toggle', $slider) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $slider->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $slider->is_active ? 'مفعل' : 'معطل' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>{{ $slider->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.sliders.show', $slider) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.sliders.edit', $slider) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.sliders.destroy', $slider) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا السلايدر؟')">
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد سلايدرات</h5>
                            <p class="text-muted">ابدأ بإضافة سلايدر جديد</p>
                            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة سلايدر جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
