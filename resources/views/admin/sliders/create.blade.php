@extends('admin.layouts.app')

@section('title', 'إضافة سلايدر جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة سلايدر جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">العنوان الرئيسي <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="subtitle">العنوان الفرعي</label>
                                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                                           id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                                    @error('subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">الوصف</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="button_text">نص الزر</label>
                                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                                   id="button_text" name="button_text" value="{{ old('button_text') }}">
                                            @error('button_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="button_url">رابط الزر</label>
                                            <input type="text" class="form-control @error('button_url') is-invalid @enderror" 
                                                   id="button_url" name="button_url" value="{{ old('button_url') }}" 
                                                   placeholder="/categories/fruits-vegetables">
                                            @error('button_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="badge">الشارة</label>
                                            <input type="text" class="form-control @error('badge') is-invalid @enderror" 
                                                   id="badge" name="badge" value="{{ old('badge') }}" 
                                                   placeholder="طازج يومياً">
                                            @error('badge')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort_order">ترتيب العرض</label>
                                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="background_image">رابط الصورة الخلفية</label>
                                    <input type="url" class="form-control @error('background_image') is-invalid @enderror" 
                                           id="background_image" name="background_image" value="{{ old('background_image') }}" 
                                           placeholder="https://images.pexels.com/photos/...">
                                    @error('background_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gradient_color">لون التدرج</label>
                                    <select class="form-control @error('gradient_color') is-invalid @enderror" 
                                            id="gradient_color" name="gradient_color">
                                        <option value="">اختر لون التدرج</option>
                                        <option value="from-green-600 to-emerald-600" {{ old('gradient_color') == 'from-green-600 to-emerald-600' ? 'selected' : '' }}>أخضر</option>
                                        <option value="from-blue-500 to-cyan-500" {{ old('gradient_color') == 'from-blue-500 to-cyan-500' ? 'selected' : '' }}>أزرق</option>
                                        <option value="from-red-500 to-rose-500" {{ old('gradient_color') == 'from-red-500 to-rose-500' ? 'selected' : '' }}>أحمر</option>
                                        <option value="from-purple-500 to-pink-500" {{ old('gradient_color') == 'from-purple-500 to-pink-500' ? 'selected' : '' }}>بنفسجي</option>
                                        <option value="from-orange-500 to-yellow-500" {{ old('gradient_color') == 'from-orange-500 to-yellow-500' ? 'selected' : '' }}>برتقالي</option>
                                    </select>
                                    @error('gradient_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            مفعل
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">صورة السلايدر</label>
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        الحد الأقصى: 2MB، الأنواع المسموحة: JPG, PNG, GIF
                                    </small>
                                </div>

                                <div class="preview-container mt-3" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="معاينة الصورة" class="img-fluid rounded">
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title">معاينة السلايدر</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="slider-preview" style="height: 200px; background: linear-gradient(45deg, #e0e0e0, #f0f0f0); border-radius: 8px; position: relative; overflow: hidden;">
                                            <div class="preview-content" style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); color: white; z-index: 2;">
                                                <div id="previewBadge" class="badge badge-light mb-2" style="display: none;"></div>
                                                <h4 id="previewTitle" class="mb-2">العنوان الرئيسي</h4>
                                                <h5 id="previewSubtitle" class="mb-2" style="display: none;">العنوان الفرعي</h5>
                                                <p id="previewDescription" class="mb-3" style="display: none;">الوصف</p>
                                                <button id="previewButton" class="btn btn-light" style="display: none;">نص الزر</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ السلايدر
                        </button>
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Live preview
    const titleInput = document.getElementById('title');
    const subtitleInput = document.getElementById('subtitle');
    const descriptionInput = document.getElementById('description');
    const buttonTextInput = document.getElementById('button_text');
    const badgeInput = document.getElementById('badge');
    const gradientSelect = document.getElementById('gradient_color');
    const backgroundImageInput = document.getElementById('background_image');

    const previewTitle = document.getElementById('previewTitle');
    const previewSubtitle = document.getElementById('previewSubtitle');
    const previewDescription = document.getElementById('previewDescription');
    const previewButton = document.getElementById('previewButton');
    const previewBadge = document.getElementById('previewBadge');
    const sliderPreview = document.querySelector('.slider-preview');

    function updatePreview() {
        // Update text content
        previewTitle.textContent = titleInput.value || 'العنوان الرئيسي';
        previewSubtitle.textContent = subtitleInput.value || '';
        previewDescription.textContent = descriptionInput.value || '';
        previewButton.textContent = buttonTextInput.value || '';
        previewBadge.textContent = badgeInput.value || '';

        // Show/hide elements
        previewSubtitle.style.display = subtitleInput.value ? 'block' : 'none';
        previewDescription.style.display = descriptionInput.value ? 'block' : 'none';
        previewButton.style.display = buttonTextInput.value ? 'inline-block' : 'none';
        previewBadge.style.display = badgeInput.value ? 'inline-block' : 'none';

        // Update background
        let backgroundStyle = '';
        if (backgroundImageInput.value) {
            backgroundStyle = `linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(${backgroundImageInput.value})`;
        } else if (gradientSelect.value) {
            const gradient = gradientSelect.value.replace('from-', '').replace('to-', '');
            const [fromColor, toColor] = gradient.split(' to-');
            backgroundStyle = `linear-gradient(45deg, var(--${fromColor}), var(--${toColor}))`;
        }
        
        if (backgroundStyle) {
            sliderPreview.style.background = backgroundStyle;
            sliderPreview.style.backgroundSize = 'cover';
            sliderPreview.style.backgroundPosition = 'center';
        }
    }

    // Add event listeners
    [titleInput, subtitleInput, descriptionInput, buttonTextInput, badgeInput, gradientSelect, backgroundImageInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    // Initial preview update
    updatePreview();
});
</script>
@endsection
