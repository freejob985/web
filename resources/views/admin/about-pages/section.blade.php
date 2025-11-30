@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        إدارة قسم: {{ $sectionName }}
                    </h3>
                    <p class="text-muted mb-0">إدارة محتوى قسم {{ $sectionName }} في صفحة "من نحن"</p>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <a href="{{ route('admin.about-pages.create') }}?section={{ $section }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                إضافة محتوى جديد
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.about-pages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للقائمة الرئيسية
                            </a>
                        </div>
                    </div>

                    @if($aboutPages->count() > 0)
                        <div class="row">
                            @foreach($aboutPages as $item)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 {{ $item->is_active ? '' : 'opacity-50' }}">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $item->title }}</h5>
                                            <p class="card-text text-muted small flex-grow-1">
                                                {{ Str::limit($item->content, 100) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-1">
                                                    <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $item->is_active ? 'مفعل' : 'معطل' }}
                                                    </span>
                                                    <span class="badge bg-info">ترتيب: {{ $item->order }}</span>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.about-pages.show', $item) }}" class="btn btn-outline-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.about-pages.edit', $item) }}" class="btn btn-outline-primary" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.about-pages.toggle-status', $item) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-{{ $item->is_active ? 'warning' : 'success' }}" title="{{ $item->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                            <i class="fas fa-{{ $item->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.about-pages.destroy', $item) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحتوى؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 4rem;"></i>
                            <h4 class="text-muted">لا يوجد محتوى في هذا القسم</h4>
                            <p class="text-muted mb-4">ابدأ بإضافة محتوى لقسم {{ $sectionName }}</p>
                            <a href="{{ route('admin.about-pages.create') }}?section={{ $section }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                إضافة محتوى جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
