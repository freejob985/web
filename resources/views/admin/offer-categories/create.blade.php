@extends('admin.layouts.app')

@section('title', 'إضافة قسم عرض جديد')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 font-weight-bold">
                                <i class="fas fa-plus-circle me-3"></i>إضافة قسم عرض جديد
                            </h1>
                            <p class="mb-0 opacity-75">إنشاء قسم جديد لتنظيم العروض والخصومات</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.offer-categories.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Instructions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading">إرشادات إنشاء قسم العروض</h5>
                        <ul class="mb-0">
                            <li><strong>اسم القسم:</strong> اختر اسماً واضحاً ومميزاً للقسم</li>
                            <li><strong>الرابط (Slug):</strong> سيتم إنشاؤه تلقائياً من اسم القسم، أو يمكنك تخصيصه</li>
                            <li><strong>الصورة:</strong> اختر صورة عالية الجودة بحجم لا يزيد عن 2 ميجابايت</li>
                            <li><strong>اللون:</strong> اختر لوناً مناسباً لتمييز القسم في الواجهة</li>
                            <li><strong>ترتيب العرض:</strong> رقم أقل يعني ظهور أولى في القائمة</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-dark">
                                <i class="fas fa-edit me-2 text-primary"></i>
                                بيانات القسم
                            </h5>
                            <p class="mb-0 text-muted small">املأ جميع البيانات المطلوبة لإنشاء القسم</p>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-asterisk text-danger me-1"></i>الحقول المطلوبة
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.offer-categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                                </h6>
                                <hr class="mt-0 mb-3" style="border-color: #3498db;">
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label font-weight-bold">
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            اسم القسم <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required
                                               placeholder="مثال: عروض الإلكترونيات">
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb text-warning me-1"></i>
                                            اختر اسماً واضحاً ومميزاً للقسم
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug" class="form-label font-weight-bold">
                                            <i class="fas fa-link text-primary me-2"></i>
                                            الرابط (Slug)
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('slug') is-invalid @enderror" 
                                               id="slug" name="slug" value="{{ old('slug') }}"
                                               placeholder="electronics-offers">
                                        <div class="form-text">
                                            <i class="fas fa-magic text-info me-1"></i>
                                            سيتم إنشاؤه تلقائياً من اسم القسم إذا ترك فارغاً
                                        </div>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-align-left me-2"></i>الوصف والتفاصيل
                                </h6>
                                <hr class="mt-0 mb-3" style="border-color: #3498db;">
                            </div>
                            
                            <div class="form-group">
                                <label for="description" class="form-label font-weight-bold">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    وصف القسم
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4"
                                          placeholder="اكتب وصفاً مختصراً عن هذا القسم وأنواع العروض التي يحتويها...">{{ old('description') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info me-1"></i>
                                    وصف مختصر يساعد العملاء على فهم محتوى القسم
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Visual Settings Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-palette me-2"></i>الإعدادات المرئية
                                </h6>
                                <hr class="mt-0 mb-3" style="border-color: #3498db;">
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-label font-weight-bold">
                                            <i class="fas fa-image text-primary me-2"></i>
                                            صورة القسم
                                        </label>
                                        <div class="image-upload-area border-2 border-dashed border-primary rounded p-4 text-center">
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*" style="display: none;">
                                            <div class="upload-placeholder" onclick="document.getElementById('image').click()">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                <p class="mb-2">انقر لاختيار صورة أو اسحب الصورة هنا</p>
                                                <small class="text-muted">JPG, PNG, GIF - حد أقصى 2MB</small>
                                            </div>
                                            <div class="image-preview" style="display: none;">
                                                <img id="preview-image" src="" alt="معاينة الصورة" class="img-fluid rounded" style="max-height: 200px;">
                                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                                    <i class="fas fa-trash me-1"></i>إزالة الصورة
                                                </button>
                                            </div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color" class="form-label font-weight-bold">
                                            <i class="fas fa-paint-brush text-primary me-2"></i>
                                            لون القسم
                                        </label>
                                        <div class="color-options">
                                            <div class="row g-2">
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-primary" {{ old('color', 'bg-primary') == 'bg-primary' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-primary"></div>
                                                        <span>أزرق</span>
                                                    </label>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-success" {{ old('color') == 'bg-success' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-success"></div>
                                                        <span>أخضر</span>
                                                    </label>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-danger" {{ old('color') == 'bg-danger' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-danger"></div>
                                                        <span>أحمر</span>
                                                    </label>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-warning" {{ old('color') == 'bg-warning' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-warning"></div>
                                                        <span>أصفر</span>
                                                    </label>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-info" {{ old('color') == 'bg-info' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-info"></div>
                                                        <span>سماوي</span>
                                                    </label>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <label class="color-option">
                                                        <input type="radio" name="color" value="bg-secondary" {{ old('color') == 'bg-secondary' ? 'checked' : '' }}>
                                                        <div class="color-preview bg-secondary"></div>
                                                        <span>رمادي</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('color')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Settings Section -->
                        <div class="form-section mb-5">
                            <div class="section-header mb-4">
                                <h6 class="text-primary font-weight-bold mb-2">
                                    <i class="fas fa-cogs me-2"></i>إعدادات العرض
                                </h6>
                                <hr class="mt-0 mb-3" style="border-color: #3498db;">
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sort_order" class="form-label font-weight-bold">
                                            <i class="fas fa-sort-numeric-down text-primary me-2"></i>
                                            ترتيب العرض
                                        </label>
                                        <input type="number" class="form-control form-control-lg @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                               placeholder="0">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info me-1"></i>
                                            رقم أقل يعني ظهور أولى في القائمة (0 = الأول)
                                        </div>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold">
                                            <i class="fas fa-toggle-on text-primary me-2"></i>
                                            حالة القسم
                                        </label>
                                        <div class="status-toggle-container">
                                            <div class="form-check form-switch form-check-lg">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label font-weight-bold" for="is_active">
                                                    <span class="active-text text-success">نشط</span>
                                                    <span class="inactive-text text-muted">غير نشط</span>
                                                </label>
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-eye text-success me-1"></i>
                                                القسم النشط سيظهر للعملاء في الموقع
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center p-4 bg-light rounded">
                                <a href="{{ route('admin.offer-categories.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة للقائمة
                                </a>
                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-outline-primary btn-lg" onclick="previewCategory()">
                                        <i class="fas fa-eye me-2"></i>
                                        معاينة
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ القسم
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    position: relative;
}

.section-header {
    position: relative;
}

.image-upload-area {
    cursor: pointer;
    transition: all 0.3s ease;
}

.image-upload-area:hover {
    border-color: #2980b9 !important;
    background-color: rgba(52, 152, 219, 0.05);
}

.color-option {
    display: block;
    text-align: center;
    cursor: pointer;
    padding: 10px;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.color-option:hover {
    border-color: #3498db;
    background-color: rgba(52, 152, 219, 0.05);
}

.color-option input[type="radio"] {
    display: none;
}

.color-option input[type="radio"]:checked + .color-preview {
    transform: scale(1.2);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
}

.color-preview {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin: 0 auto 8px;
    transition: all 0.3s ease;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.color-option span {
    font-size: 0.85rem;
    font-weight: 500;
}

.status-toggle-container .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.status-toggle-container .active-text {
    display: none;
}

.status-toggle-container .inactive-text {
    display: inline;
}

.status-toggle-container .form-check-input:checked ~ .form-check-label .active-text {
    display: inline;
}

.status-toggle-container .form-check-input:checked ~ .form-check-label .inactive-text {
    display: none;
}

.form-actions {
    margin-top: 2rem;
    border-top: 2px solid #e9ecef;
    padding-top: 2rem;
}

.form-control-lg, .form-select-lg {
    padding: 12px 16px;
    font-size: 1rem;
}

.alert-heading {
    font-size: 1.1rem;
    margin-bottom: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\u0600-\u06FF\s]/g, '') // Keep Arabic and English letters, numbers, spaces
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .trim();
        
        document.getElementById('slug').value = slug;
    });

    // Image upload preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.querySelector('.upload-placeholder').style.display = 'none';
                document.querySelector('.image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        if (!name) {
            e.preventDefault();
            alert('يرجى إدخال اسم القسم');
            document.getElementById('name').focus();
            return false;
        }
    });
});

function removeImage() {
    document.getElementById('image').value = '';
    document.querySelector('.upload-placeholder').style.display = 'block';
    document.querySelector('.image-preview').style.display = 'none';
}

function previewCategory() {
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const color = document.querySelector('input[name="color"]:checked')?.value || 'bg-primary';
    
    if (!name) {
        alert('يرجى إدخال اسم القسم أولاً');
        return;
    }
    
    // Create preview modal
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">معاينة القسم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="badge ${color} px-4 py-3 mb-3" style="font-size: 1.1rem;">
                                ${name}
                            </div>
                            ${description ? `<p class="text-muted">${description}</p>` : ''}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}
</script>
@endsection
