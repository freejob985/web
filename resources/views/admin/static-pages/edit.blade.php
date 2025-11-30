@extends('admin.layouts.app')

@section('title', 'تعديل الصفحة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل الصفحة: {{ $staticPage->title }}</h3>
                    <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
                <div class="card-body">
                    @if($staticPage->is_fixed)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            هذه صفحة ثابتة ولا يمكن تعديلها.
                        </div>
                    @endif

                    <form action="{{ route('admin.static-pages.update', $staticPage) }}" method="POST" id="pageForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">الرابط (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $staticPage->slug) }}" 
                                           {{ $staticPage->is_fixed ? 'readonly' : 'required' }}>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">مثال: about-us, contact-us</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">العنوان <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $staticPage->title) }}" 
                                           {{ $staticPage->is_fixed ? 'readonly' : 'required' }}>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content">المحتوى <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15" 
                                      {{ $staticPage->is_fixed ? 'readonly' : 'required' }}>{{ old('content', $staticPage->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description">وصف Meta</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" name="meta_description" rows="3" 
                                              {{ $staticPage->is_fixed ? 'readonly' : '' }}>{{ old('meta_description', $staticPage->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_keywords">كلمات مفتاحية</label>
                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                           id="meta_keywords" name="meta_keywords" 
                                           value="{{ old('meta_keywords', $staticPage->meta_keywords) }}"
                                           {{ $staticPage->is_fixed ? 'readonly' : '' }}>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">مفصولة بفواصل</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $staticPage->is_active) ? 'checked' : '' }}
                                               {{ $staticPage->is_fixed ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            الصفحة نشطة
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', $staticPage->sort_order) }}" 
                                           min="0" {{ $staticPage->is_fixed ? 'readonly' : '' }}>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($staticPage->is_fixed)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                هذه صفحة ثابتة. يمكنك فقط عرض المحتوى ولكن لا يمكن تعديله.
                            </div>
                        @else
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
