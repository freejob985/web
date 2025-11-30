@extends('admin.layouts.app')

@section('title', 'إضافة ماركة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>إضافة ماركة جديدة
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>اسم الماركة <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="أدخل اسم الماركة" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="country" class="form-label fw-bold">
                                        <i class="fas fa-flag me-1"></i>البلد
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country') }}" 
                                           placeholder="أدخل بلد الماركة">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="website" class="form-label fw-bold">
                                        <i class="fas fa-globe me-1"></i>الموقع الإلكتروني
                                    </label>
                                    <input type="url" class="form-control form-control-lg @error('website') is-invalid @enderror" 
                                           id="website" name="website" value="{{ old('website') }}" 
                                           placeholder="https://example.com">
                                    <div class="form-text">الموقع الرسمي للماركة (اختياري)</div>
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sort_order" class="form-label fw-bold">
                                        <i class="fas fa-sort-numeric-up me-1"></i>ترتيب العرض
                                    </label>
                                    <input type="number" class="form-control form-control-lg @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                           min="0" placeholder="0">
                                    <div class="form-text">كلما قل الرقم، كلما ظهرت الماركة في المقدمة</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-align-right me-2"></i>الوصف
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-right me-1"></i>وصف الماركة
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="أدخل وصف مختصر عن الماركة">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-images me-2"></i>الشعار
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="logo" class="form-label fw-bold">
                                        <i class="fas fa-image me-1"></i>شعار الماركة
                                    </label>
                                    <input type="file" class="form-control form-control-lg @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    <div class="form-text">الصور المسموحة: JPG, PNG, GIF. الحد الأقصى: 2MB</div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-cog me-2"></i>الإعدادات
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <i class="fas fa-toggle-on me-1"></i>الماركة نشطة
                                        </label>
                                    </div>
                                    <div class="form-text">الماركة غير النشطة لن تظهر في الموقع</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>حفظ الماركة
                                    </button>
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-lg px-4">
                                        <i class="fas fa-arrow-left me-2"></i>رجوع
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
