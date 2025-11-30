@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تعديل محتوى صفحة "من نحن"
                    </h3>
                    <p class="text-muted mb-0">تعديل المحتوى: {{ $aboutPage->title }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.about-pages.update', $aboutPage) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="section" class="form-label">القسم <span class="text-danger">*</span></label>
                                    <select name="section" id="section" class="form-select @error('section') is-invalid @enderror" required>
                                        @foreach($sections as $key => $name)
                                            <option value="{{ $key }}" {{ old('section', $aboutPage->section) == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">العنوان <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $aboutPage->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">المحتوى</label>
                                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" 
                                              rows="8" placeholder="اكتب محتوى القسم هنا...">{{ old('content', $aboutPage->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">ترتيب العرض</label>
                                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" 
                                                   value="{{ old('order', $aboutPage->order) }}" min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                                       {{ old('is_active', $aboutPage->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    مفعل
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">الصورة</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*" onchange="previewImage(this)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($aboutPage->image)
                                        <div class="mt-2">
                                            <p class="text-muted small">الصورة الحالية:</p>
                                            <img src="{{ Storage::url($aboutPage->image) }}" alt="{{ $aboutPage->title }}" 
                                                 class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <img id="imagePreview" src="" alt="معاينة الصورة الجديدة" class="img-fluid rounded" style="display: none; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">بيانات إضافية</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">عنوان SEO</label>
                                            <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror" 
                                                   value="{{ old('meta_title', $aboutPage->meta_title) }}">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">وصف SEO</label>
                                            <textarea name="meta_description" id="meta_description" class="form-control @error('meta_description') is-invalid @enderror" 
                                                      rows="3">{{ old('meta_description', $aboutPage->meta_description) }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">كلمات مفتاحية</label>
                                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                   value="{{ old('meta_keywords', $aboutPage->meta_keywords) }}" placeholder="كلمة1, كلمة2, كلمة3">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.about-pages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للقائمة
                            </a>
                            <div>
                                <a href="{{ route('admin.about-pages.show', $aboutPage) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-2"></i>
                                    عرض
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ التغييرات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
