@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        عرض محتوى صفحة "من نحن"
                    </h3>
                    <p class="text-muted mb-0">{{ $aboutPage->title }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>تفاصيل المحتوى</h5>
                                <hr>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>القسم:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-primary">
                                            {{ $sections[$aboutPage->section] ?? $aboutPage->section }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>العنوان:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $aboutPage->title }}
                                    </div>
                                </div>

                                @if($aboutPage->content)
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>المحتوى:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="border rounded p-3 bg-light">
                                                {!! nl2br(e($aboutPage->content)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>الحالة:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge {{ $aboutPage->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $aboutPage->is_active ? 'مفعل' : 'معطل' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>ترتيب العرض:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $aboutPage->order }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>تاريخ الإنشاء:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $aboutPage->created_at->format('Y-m-d H:i:s') }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>آخر تحديث:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $aboutPage->updated_at->format('Y-m-d H:i:s') }}
                                    </div>
                                </div>
                            </div>

                            @if($aboutPage->meta_title || $aboutPage->meta_description || $aboutPage->meta_keywords)
                                <div class="mb-4">
                                    <h5>بيانات SEO</h5>
                                    <hr>
                                    
                                    @if($aboutPage->meta_title)
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <strong>عنوان SEO:</strong>
                                            </div>
                                            <div class="col-sm-9">
                                                {{ $aboutPage->meta_title }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($aboutPage->meta_description)
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <strong>وصف SEO:</strong>
                                            </div>
                                            <div class="col-sm-9">
                                                {{ $aboutPage->meta_description }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($aboutPage->meta_keywords)
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <strong>كلمات مفتاحية:</strong>
                                            </div>
                                            <div class="col-sm-9">
                                                {{ $aboutPage->meta_keywords }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($aboutPage->image)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">الصورة</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img src="{{ Storage::url($aboutPage->image) }}" alt="{{ $aboutPage->title }}" 
                                             class="img-fluid rounded" style="max-height: 300px;">
                                    </div>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الإجراءات</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.about-pages.edit', $aboutPage) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-2"></i>
                                            تعديل المحتوى
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.about-pages.toggle-status', $aboutPage) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $aboutPage->is_active ? 'warning' : 'success' }} w-100">
                                                <i class="fas fa-{{ $aboutPage->is_active ? 'pause' : 'play' }} me-2"></i>
                                                {{ $aboutPage->is_active ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('admin.about-pages.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-right me-2"></i>
                                            العودة للقائمة
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.about-pages.destroy', $aboutPage) }}" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المحتوى؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash me-2"></i>
                                                حذف المحتوى
                                            </button>
                                        </form>
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
