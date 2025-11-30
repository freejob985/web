@extends('admin.layouts.app')

@section('title', 'إضافة فئة أعمال جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>إضافة فئة أعمال جديدة
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.business-categories.store') }}" method="POST">
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
                                        <i class="fas fa-tag me-1"></i>اسم فئة الأعمال <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="أدخل اسم فئة الأعمال" required>
                                    @error('name')
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
                                    <div class="form-text">كلما قل الرقم، كلما ظهرت فئة الأعمال في المقدمة</div>
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
                                        <i class="fas fa-align-right me-1"></i>وصف فئة الأعمال
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="أدخل وصف مختصر عن فئة الأعمال">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Icon Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-icons me-2"></i>الأيقونة
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label fw-bold">
                                        <i class="fas fa-icons me-1"></i>أيقونة فئة الأعمال
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon') }}" 
                                               placeholder="مثال: fas fa-briefcase" readonly>
                                        <span class="input-group-text bg-white" id="icon-preview">
                                            <i class="fas fa-icons"></i>
                                        </span>
                                        <button type="button" class="btn btn-primary" onclick="openIconPicker('icon', 'icon-preview')">
                                            <i class="fas fa-search"></i> اختيار أيقونة
                                        </button>
                                    </div>
                                    <div class="form-text">انقر على زر "اختيار أيقونة" لفتح مكتبة الأيقونات</div>
                                    @error('icon')
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
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <i class="fas fa-toggle-on me-1"></i>فئة الأعمال نشطة
                                        </label>
                                    </div>
                                    <div class="form-text">فئة الأعمال غير النشطة لن تظهر في الموقع</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>حفظ فئة الأعمال
                                    </button>
                                    <a href="{{ route('admin.business-categories.index') }}" class="btn btn-secondary btn-lg px-4">
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

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconModalLabel">
                    <i class="fas fa-icons me-2"></i>اختيار أيقونة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <input type="text" class="form-control" id="iconSearch" placeholder="البحث عن أيقونة...">
                    </div>
                </div>
                <div class="row" id="iconGrid">
                    <!-- Icons will be loaded here -->
                </div>
            </div>
                                        <div class="row" id="iconGrid">
                    <!-- Icons will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Icon Picker Simple Library -->
<script src="{{ asset('js/icon-picker-simple.js') }}"></script>
@endpush