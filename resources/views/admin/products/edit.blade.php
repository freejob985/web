@extends('admin.layouts.app')

@section('content')
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit me-2"></i>تعديل المنتج</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Basic Information Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i>اسم المنتج
                    </label>
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" required value="{{ old('name', $product->name) }}" placeholder="أدخل اسم المنتج" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-barcode me-1"></i>SKU
                    </label>
                    <div class="input-group">
                        <input class="form-control @error('sku') is-invalid @enderror" type="text" name="sku" id="sku" required value="{{ old('sku', $product->sku) }}" placeholder="كود المنتج" />
                        <button class="btn btn-outline-secondary" type="button" id="generate-sku">
                            <i class="fas fa-sync-alt"></i> توليد تلقائي
                        </button>
                    </div>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-qrcode me-1"></i>الباركود
                    </label>
                    <input class="form-control @error('barcode') is-invalid @enderror" type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" placeholder="الباركود (اختياري)" />
                    @error('barcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-align-right me-1"></i>الوصف
                    </label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="وصف المنتج">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing & Inventory Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>التسعير والمخزون</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-1"></i>السعر
                    </label>
                    <input class="form-control @error('price') is-invalid @enderror" type="number" step="0.001" name="price" required value="{{ old('price', $product->price) }}" placeholder="0.000" />
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i>السعر الأصلي
                    </label>
                    <input class="form-control @error('original_price') is-invalid @enderror" type="number" step="0.001" name="original_price" value="{{ old('original_price', $product->original_price) }}" placeholder="0.000" />
                    @error('original_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-boxes me-1"></i>المخزون
                    </label>
                    <input class="form-control @error('stock') is-invalid @enderror" type="number" name="stock" required value="{{ old('stock', $product->stock) }}" placeholder="0" />
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-tags me-2"></i>التصنيفات</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags me-1"></i>القسم الرئيسي
                    </label>
                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                        <option value="">اختر القسم الرئيسي</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i>القسم الفرعي
                    </label>
                    <select class="form-control @error('subcategory_id') is-invalid @enderror" name="subcategory_id" id="subcategory_id">
                        <option value="">اختر القسم الفرعي</option>
                        @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Location Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>الموقع</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>المحافظة
                    </label>
                    <select class="form-control @error('governorate_id') is-invalid @enderror" name="governorate_id" id="governorate_id">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ old('governorate_id', $product->governorate_id) == $governorate->id ? 'selected' : '' }}>
                                {{ $governorate->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('governorate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker me-1"></i>المدينة
                    </label>
                    <select class="form-control @error('city_id') is-invalid @enderror" name="city_id" id="city_id">
                        <option value="">اختر المدينة</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $product->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>تفاصيل المنتج</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-copyright me-1"></i>الماركة
                    </label>
                    <select class="form-control @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id">
                        <option value="">اختر الماركة</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-globe me-1"></i>المنشأ
                    </label>
                    <input class="form-control @error('origin') is-invalid @enderror" type="text" name="origin" required value="{{ old('origin', $product->origin) }}" placeholder="منشأ المنتج" />
                    @error('origin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-store me-1"></i>المورد
                    </label>
                    <select class="form-select @error('vendor_id') is-invalid @enderror" name="vendor_id" required>
                        <option value="">اختر المورد</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" @selected(old('vendor_id', $product->vendor_id) == $vendor->id)>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-weight me-1"></i>الوزن
                    </label>
                    <input class="form-control @error('weight') is-invalid @enderror" type="number" step="0.001" name="weight" value="{{ old('weight', $product->weight) }}" placeholder="0.000" />
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-ruler me-1"></i>الوحدة
                    </label>
                    <input class="form-control @error('unit') is-invalid @enderror" type="text" name="unit" required value="{{ old('unit', $product->unit) }}" placeholder="كيلو" />
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Images Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-images me-2"></i>الصور</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-image me-1"></i>صورة المنتج الرئيسية
                    </label>
                    <div id="dropzone" class="dropzone">
                        <div class="dz-message">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                            <h4>اسحب وأفلت الصورة هنا</h4>
                            <p>أو انقر لاختيار الصورة</p>
                        </div>
                    </div>
                    <input type="hidden" name="image" id="image-input" value="{{ $product->image }}" />
                    
                    @if($product->image)
                        <div class="mt-3">
                            <div class="current-image-preview">
                                <img src="/storage/{{ $product->image }}" alt="الصورة الحالية" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentImage()">
                                        <i class="fas fa-trash"></i> إزالة الصورة
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="openImageLightbox('/storage/{{ $product->image }}')">
                                        <i class="fas fa-eye"></i> عرض
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-images me-1"></i>معرض الصور
                    </label>
                    @if($product->images && is_array($product->images) && count($product->images) > 0)
                        <div class="row" id="existing-gallery">
                            @foreach($product->images as $index => $imagePath)
                                <div class="col-md-6 mb-3" id="gallery-item-{{ $index }}">
                                    <div class="gallery-item">
                                        <img src="/storage/{{ $imagePath }}" alt="صورة المعرض" class="img-thumbnail gallery-image" style="width: 100%; height: 150px; object-fit: cover;">
                                        <div class="gallery-overlay">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="openImageLightbox('/storage/{{ $imagePath }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGalleryImage({{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-images fa-3x mb-3"></i>
                            <p>لا توجد صور في المعرض</p>
                        </div>
                    @endif
                    
                    <!-- Add new images -->
                    <div class="mt-3">
                        <label class="form-label">إضافة صور جديدة</label>
                        <div id="gallery-dropzone" class="gallery-dropzone">
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p>اسحب وأفلت الصور هنا</p>
                                <small>أو انقر لاختيار الصور</small>
                            </div>
                        </div>
                        <input type="hidden" name="images" id="images-input" value="{{ is_array($product->images) ? json_encode($product->images) : $product->images }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-apple-alt me-1"></i>المعلومات الغذائية
                    </label>
                    <textarea name="nutritional_info" class="form-control" rows="4" placeholder="المعلومات الغذائية للمنتج">{{ old('nutritional_info', $product->nutritional_info) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i>تاريخ الانتهاء
                    </label>
                    <input class="form-control" type="date" name="expiry_date" value="{{ old('expiry_date', $product->expiry_date) }}" />
                </div>
            </div>
        </div>
    </div>

    <!-- Product Settings Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>إعدادات المنتج</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input @error('is_featured') is-invalid @enderror" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) />
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star me-1"></i>منتج مميز
                        </label>
                        @error('is_featured')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input @error('is_fresh') is-invalid @enderror" type="checkbox" name="is_fresh" id="is_fresh" value="1" @checked(old('is_fresh', $product->is_fresh)) />
                        <label class="form-check-label" for="is_fresh">
                            <i class="fas fa-leaf me-1"></i>منتج طازج
                        </label>
                        @error('is_fresh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $product->is_active)) />
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-check-circle me-1"></i>نشط
                        </label>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle me-1"></i>حالة المنتج
                    </label>
                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                        <option value="pending" {{ old('status', $product->status) == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="approved" {{ old('status', $product->status) == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                        <option value="rejected" {{ old('status', $product->status) == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex gap-3">
        <button class="btn btn-primary" type="submit">
            <i class="fas fa-save me-2"></i>حفظ التعديلات
        </button>
        <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">
            <i class="fas fa-times me-2"></i>إلغاء
        </a>
    </div>
</form>

<style>
/* Global Font */
* {
    font-family: 'Cairo', sans-serif !important;
}

body {
    font-family: 'Cairo', sans-serif !important;
}

/* Font Awesome Icons Priority */
.fas, .fa, .far, .fab, .fal, .fad {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
    font-weight: 900 !important;
}

.far {
    font-weight: 400 !important;
}

.fab {
    font-family: "Font Awesome 6 Brands" !important;
    font-weight: 400 !important;
}

/* Ensure icons display properly */
i[class*="fa-"] {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
    font-weight: 900 !important;
    font-style: normal !important;
}

i[class*="far"] {
    font-weight: 400 !important;
}

i[class*="fab"] {
    font-family: "Font Awesome 6 Brands" !important;
    font-weight: 400 !important;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Cairo', sans-serif !important;
}

p, span, div, label, input, textarea, select, button {
    font-family: 'Cairo', sans-serif !important;
}

.card, .card-header, .card-body {
    font-family: 'Cairo', sans-serif !important;
}

.alert, .form-check, .form-check-label {
    font-family: 'Cairo', sans-serif !important;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    font-family: 'Cairo', sans-serif !important;
}

.form-label i {
    color: #3498db;
    font-size: 0.9rem;
    margin-left: 8px;
    width: 16px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
    padding: 10px 15px;
    font-family: 'Cairo', sans-serif !important;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
    display: flex;
    align-items: center;
}

.form-check-label i {
    color: #6c757d;
    margin-left: 8px;
    font-size: 0.8rem;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
    font-family: 'Cairo', sans-serif !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Dropzone Styles */
.dropzone {
    border: 2px dashed #3498db;
    border-radius: 10px;
    background: #f8f9fa;
    padding: 40px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropzone:hover {
    border-color: #2980b9;
    background: #e3f2fd;
}

.dropzone.dz-drag-hover {
    border-color: #27ae60;
    background: #e8f5e8;
}

.dz-message {
    color: #7f8c8d;
}

.dz-message h4 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.dz-preview {
    display: none;
}

.dz-preview.dz-image-preview {
    display: block;
    position: relative;
    margin: 10px;
}

.dz-preview .dz-image {
    border-radius: 8px;
    overflow: hidden;
}

.dz-preview .dz-image img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.dz-preview .dz-details {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dz-preview:hover .dz-details {
    opacity: 1;
}

.dz-remove {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

/* TinyMCE Custom Styles */
.tox-tinymce {
    border-radius: 10px !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.tox .tox-toolbar__primary {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.tox .tox-button {
    border-radius: 6px !important;
    transition: all 0.3s ease !important;
}

.tox .tox-button:hover {
    background: #3498db !important;
    color: white !important;
    transform: translateY(-1px) !important;
}

.tox .tox-edit-area__iframe {
    border-radius: 0 0 10px 10px !important;
}

.tox .tox-statusbar {
    background: #f8f9fa !important;
    border-top: 1px solid #dee2e6 !important;
    border-radius: 0 0 10px 10px !important;
}

/* Validation Error Styles */
.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
    font-family: 'Cairo', sans-serif;
}

.field-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
    display: block;
    font-family: 'Cairo', sans-serif;
}

/* Gallery Styles */
.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.gallery-item:hover {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.gallery-image {
    cursor: pointer;
    transition: all 0.3s ease;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-dropzone {
    border: 2px dashed #3498db;
    border-radius: 10px;
    background: #f8f9fa;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-dropzone:hover {
    border-color: #2980b9;
    background: #e3f2fd;
}

.current-image-preview {
    text-align: center;
}

.current-image-preview img {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Section Cards Styling */
.card {
    margin-bottom: 1.5rem;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem 1.5rem;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.card-header i {
    color: rgba(255, 255, 255, 0.9);
}

.card-body {
    padding: 1.5rem;
}

/* Form Section Spacing */
.mb-4 {
    margin-bottom: 2rem !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
}
</style>

<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">

<!-- TinyMCE CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/skins/ui/oxide/content.min.css">

<!-- Lightbox2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">

<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<!-- TinyMCE JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.0/tinymce.min.js"></script>

<!-- Lightbox2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<script>
Dropzone.autoDiscover = false;

document.addEventListener('DOMContentLoaded', function() {
    // Show loading overlay
    function showLoading() {
        if (document.getElementById('loading-overlay')) {
            document.getElementById('loading-overlay').style.display = 'flex';
        }
    }
    
    // Hide loading overlay
    function hideLoading() {
        if (document.getElementById('loading-overlay')) {
            document.getElementById('loading-overlay').style.display = 'none';
        }
    }

    // Initialize TinyMCE
    tinymce.init({
        selector: '#description',
        language: 'ar',
        directionality: 'rtl',
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | image | link | emoticons',
        content_style: 'body { font-family: "Cairo", sans-serif !important; font-size: 14px; }',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Auto-generate SKU functionality
    if (document.getElementById('generate-sku')) {
        document.getElementById('generate-sku').addEventListener('click', function() {
            const timestamp = Date.now();
            const random = Math.floor(Math.random() * 1000);
            const sku = 'PRD-' + timestamp + '-' + random;
            document.getElementById('sku').value = sku;
        });
    }

    // Dynamic city loading based on governorate
    if (document.getElementById('governorate_id')) {
        document.getElementById('governorate_id').addEventListener('change', function() {
            const governorateId = this.value;
            const citySelect = document.getElementById('city_id');
            
            // Clear existing options
            citySelect.innerHTML = '<option value="">اختر المدينة</option>';
            
            if (governorateId) {
                showLoading();
                
                fetch(`/admin/governorates/${governorateId}/cities`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name_ar;
                        citySelect.appendChild(option);
                    });
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                });
            }
        });
    }

    // Dynamic subcategory loading based on category
    if (document.getElementById('category_id')) {
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const subcategorySelect = document.getElementById('subcategory_id');
            
            // Clear existing options
            subcategorySelect.innerHTML = '<option value="">اختر القسم الفرعي</option>';
            
            if (categoryId) {
                showLoading();
                
                fetch(`/admin/categories/${categoryId}/subcategories`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name_ar;
                            subcategorySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'لا توجد أقسام فرعية';
                        subcategorySelect.appendChild(option);
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'خطأ في تحميل الأقسام الفرعية';
                    subcategorySelect.appendChild(option);
                    hideLoading();
                });
            }
        });
    }

    // Initialize main image Dropzone
    const dropzone = new Dropzone("#dropzone", {
        url: "/admin/products/upload-image",
        maxFiles: 1,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "",
        dictRemoveFile: "إزالة",
        dictCancelUpload: "إلغاء",
        dictUploadCanceled: "تم إلغاء الرفع",
        dictInvalidFileType: "نوع الملف غير مدعوم",
        dictFileTooBig: "الملف كبير جداً",
        dictMaxFilesExceeded: "يمكن رفع ملف واحد فقط",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function() {
            this.on("success", function(file, response) {
                console.log('Image uploaded successfully:', response);
                if (response.success && response.path) {
                    document.getElementById('image-input').value = response.path;
                } else {
                    console.error('Upload failed:', response);
                }
            });
            
            this.on("error", function(file, errorMessage) {
                console.error('Upload error:', errorMessage);
                alert('فشل في رفع الصورة: ' + errorMessage);
            });
            
            this.on("removedfile", function(file) {
                document.getElementById('image-input').value = '';
            });
        }
    });

    // Form submission with loading
    document.querySelector('form').addEventListener('submit', function() {
        showLoading();
    });

    // Initialize gallery Dropzone
    const galleryDropzone = new Dropzone("#gallery-dropzone", {
        url: "/admin/products/upload-image",
        maxFiles: 10,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "",
        dictRemoveFile: "إزالة",
        dictCancelUpload: "إلغاء",
        dictUploadCanceled: "تم إلغاء الرفع",
        dictInvalidFileType: "نوع الملف غير مدعوم",
        dictFileTooBig: "الملف كبير جداً",
        dictMaxFilesExceeded: "يمكن رفع 10 ملفات كحد أقصى",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function() {
            this.on("success", function(file, response) {
                console.log('Gallery image uploaded successfully:', response);
                if (response.success && response.path) {
                    addImageToGallery(response.path);
                } else {
                    console.error('Gallery upload failed:', response);
                }
            });
            
            this.on("error", function(file, errorMessage) {
                console.error('Gallery upload error:', errorMessage);
                alert('فشل في رفع الصورة: ' + errorMessage);
            });
        }
    });
});

// Gallery management functions
function addImageToGallery(imagePath) {
    const existingGallery = document.getElementById('existing-gallery');
    if (!existingGallery) {
        // Create gallery container if it doesn't exist
        const galleryCard = document.querySelector('.card .card-body');
        const newGallery = document.createElement('div');
        newGallery.className = 'row';
        newGallery.id = 'existing-gallery';
        galleryCard.insertBefore(newGallery, galleryCard.querySelector('.mt-3'));
    }
    
    const galleryContainer = document.getElementById('existing-gallery');
    const index = galleryContainer.children.length;
    
    const galleryItem = document.createElement('div');
    galleryItem.className = 'col-md-3 mb-3';
    galleryItem.id = `gallery-item-${index}`;
    galleryItem.innerHTML = `
        <div class="gallery-item">
            <img src="/storage/${imagePath}" alt="صورة المعرض" class="img-thumbnail gallery-image" style="width: 100%; height: 150px; object-fit: cover;">
            <div class="gallery-overlay">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openImageLightbox('/storage/${imagePath}')">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGalleryImage(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    galleryContainer.appendChild(galleryItem);
    updateImagesInput();
}

function removeGalleryImage(index) {
    const galleryItem = document.getElementById(`gallery-item-${index}`);
    if (galleryItem) {
        galleryItem.remove();
        updateImagesInput();
    }
}

function removeCurrentImage() {
    document.getElementById('image-input').value = '';
    document.querySelector('.current-image-preview').style.display = 'none';
}

function openImageLightbox(imagePath) {
    // Create a temporary link for lightbox
    const link = document.createElement('a');
    link.href = imagePath;
    link.setAttribute('data-lightbox', 'gallery');
    link.click();
}

function updateImagesInput() {
    const galleryItems = document.querySelectorAll('#existing-gallery .gallery-item img');
    const imagePaths = Array.from(galleryItems).map(img => img.src.replace(window.location.origin + '/storage/', ''));
    document.getElementById('images-input').value = JSON.stringify({images: imagePaths});
}
</script>
@endsection
