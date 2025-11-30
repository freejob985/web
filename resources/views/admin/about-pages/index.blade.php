@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        إدارة صفحة "من نحن"
                    </h3>
                    <p class="text-muted mb-0">إدارة محتوى جميع أقسام صفحة "من نحن"</p>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <a href="{{ route('admin.about-pages.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                إضافة محتوى جديد
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.about-pages.section', 'hero') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-home me-1"></i> القسم الرئيسي
                            </a>
                            <a href="{{ route('admin.about-pages.section', 'story') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-book me-1"></i> قصتنا
                            </a>
                            <a href="{{ route('admin.about-pages.section', 'values') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-heart me-1"></i> قيمنا
                            </a>
                            <a href="{{ route('admin.about-pages.section', 'statistics') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-chart-bar me-1"></i> أرقامنا
                            </a>
                        </div>
                    </div>

                    @if($aboutPages->count() > 0)
                        @foreach($sections as $sectionKey => $sectionName)
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <h4 class="mb-0 text-primary">
                                        @switch($sectionKey)
                                            @case('hero')
                                                <i class="fas fa-home me-2"></i>
                                                @break
                                            @case('story')
                                                <i class="fas fa-book me-2"></i>
                                                @break
                                            @case('values')
                                                <i class="fas fa-heart me-2"></i>
                                                @break
                                            @case('statistics')
                                                <i class="fas fa-chart-bar me-2"></i>
                                                @break
                                            @case('team')
                                                <i class="fas fa-users me-2"></i>
                                                @break
                                            @case('mission')
                                                <i class="fas fa-target me-2"></i>
                                                @break
                                        @endswitch
                                        {{ $sectionName }}
                                    </h4>
                                    <span class="badge bg-secondary ms-2">{{ $aboutPages->get($sectionKey, collect())->count() }} عنصر</span>
                                </div>

                                @if($aboutPages->has($sectionKey))
                                    <div class="row">
                                        @foreach($aboutPages[$sectionKey] as $item)
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
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted">لا يوجد محتوى في هذا القسم</p>
                                        <a href="{{ route('admin.about-pages.create') }}?section={{ $sectionKey }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>
                                            إضافة محتوى
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 4rem;"></i>
                            <h4 class="text-muted">لا يوجد محتوى</h4>
                            <p class="text-muted mb-4">ابدأ بإضافة محتوى لصفحة "من نحن"</p>
                            <a href="{{ route('admin.about-pages.create') }}" class="btn btn-primary">
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
