@extends('admin.layouts.app')

@section('title', 'إضافة قسم فرعي جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>إضافة قسم فرعي جديد
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.subcategories.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="name_ar" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>الاسم (عربي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                                           placeholder="أدخل اسم القسم الفرعي بالعربية" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_en" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>الاسم (إنجليزي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name_en') is-invalid @enderror" 
                                           id="name_en" name="name_en" value="{{ old('name_en') }}" 
                                           placeholder="Enter subcategory name in English" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label fw-bold">
                                        <i class="fas fa-folder me-1"></i>القسم الرئيسي <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control form-control-lg @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">اختر القسم الرئيسي</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">اختر القسم الرئيسي الذي ينتمي إليه هذا القسم الفرعي</div>
                                    @error('category_id')
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
                                    <div class="form-text">كلما قل الرقم، كلما ظهر القسم الفرعي في المقدمة</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-images me-2"></i>الصور والأيقونات
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label fw-bold">
                                        <i class="fas fa-image me-1"></i>صورة القسم الفرعي
                                    </label>
                                    <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">الصور المسموحة: JPG, PNG, GIF. الحد الأقصى: 2MB</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label fw-bold">
                                        <i class="fas fa-icons me-1"></i>أيقونة القسم الفرعي
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon') }}" 
                                               placeholder="مثال: fas fa-tag" readonly>
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

                        <!-- Description Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-align-right me-2"></i>الوصف
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label fw-bold">
                                        <i class="fas fa-align-right me-1"></i>الوصف (عربي)
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" name="description_ar" rows="4" 
                                              placeholder="أدخل وصف القسم الفرعي بالعربية">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1"></i>الوصف (إنجليزي)
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" name="description_en" rows="4" 
                                              placeholder="Enter subcategory description in English">{{ old('description_en') }}</textarea>
                                    @error('description_en')
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <i class="fas fa-toggle-on me-1"></i>القسم الفرعي نشط
                                        </label>
                                    </div>
                                    <div class="form-text">القسم الفرعي غير النشط لن يظهر في الموقع</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>حفظ القسم الفرعي
                                    </button>
                                    <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary btn-lg px-4">
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

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="iconPickerModalLabel">
                    <i class="fas fa-icons me-2"></i>اختيار أيقونة Font Awesome
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-3 border-end">
                        <div class="p-3">
                            <h6 class="mb-3">الفئات</h6>
                            <div class="list-group list-group-flush" id="iconCategories">
                                <a href="#" class="list-group-item list-group-item-action active" data-category="all">جميع الأيقونات</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="solid">Solid</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="regular">Regular</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="brands">Brands</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="p-3 border-bottom">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="iconSearchInput" placeholder="البحث عن أيقونة...">
                            </div>
                        </div>
                        <div class="p-3" style="max-height: 500px; overflow-y: auto;">
                            <div class="row" id="iconGridContainer">
                                <!-- Icons will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="selectIconBtn" disabled>اختيار الأيقونة</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Icon Picker Styles */
    .icon-picker-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        text-align: center;
        height: 100px;
        justify-content: center;
    }
    
    .icon-picker-item:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
    }
    
    .icon-picker-item.selected {
        border-color: #28a745;
        background-color: #d4edda;
    }
    
    .icon-picker-item i {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 8px;
    }
    
    .icon-picker-item.selected i {
        color: #28a745;
    }
    
    .icon-picker-item .icon-name {
        font-size: 0.75rem;
        color: #6c757d;
        word-break: break-all;
        line-height: 1.2;
    }
    
    .icon-preview {
        display: inline-block;
        margin-left: 10px;
        font-size: 1.5rem;
        color: #007bff;
    }
    
    #iconGridContainer {
        min-height: 400px;
    }
    
    .category-filter {
        cursor: pointer;
    }
    
    .category-filter.active {
        background-color: #007bff !important;
        color: white !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Icon picker functionality
    const iconInput = $('#icon');
    const iconPickerModal = $('#iconPickerModal');
    const iconGridContainer = $('#iconGridContainer');
    const iconSearchInput = $('#iconSearchInput');
    const selectIconBtn = $('#selectIconBtn');
    const clearIconBtn = $('#clearIconBtn');
    let selectedIcon = null;
    
    // Popular Font Awesome icons
    const icons = [
        'fas fa-shopping-cart', 'fas fa-tags', 'fas fa-box', 'fas fa-gift', 'fas fa-star',
        'fas fa-heart', 'fas fa-home', 'fas fa-user', 'fas fa-cog', 'fas fa-search',
        'fas fa-phone', 'fas fa-envelope', 'fas fa-map-marker-alt', 'fas fa-clock',
        'fas fa-calendar', 'fas fa-bell', 'fas fa-bookmark', 'fas fa-thumbs-up',
        'fas fa-share', 'fas fa-download', 'fas fa-upload', 'fas fa-edit', 'fas fa-trash',
        'fas fa-plus', 'fas fa-minus', 'fas fa-check', 'fas fa-times', 'fas fa-arrow-right',
        'fas fa-arrow-left', 'fas fa-arrow-up', 'fas fa-arrow-down', 'fas fa-expand',
        'fas fa-compress', 'fas fa-refresh', 'fas fa-sync', 'fas fa-redo', 'fas fa-undo',
        'fas fa-play', 'fas fa-pause', 'fas fa-stop', 'fas fa-forward', 'fas fa-backward',
        'fas fa-volume-up', 'fas fa-volume-down', 'fas fa-volume-mute', 'fas fa-microphone',
        'fas fa-camera', 'fas fa-video', 'fas fa-image', 'fas fa-file', 'fas fa-folder',
        'fas fa-database', 'fas fa-server', 'fas fa-cloud', 'fas fa-wifi', 'fas fa-bluetooth',
        'fas fa-battery-full', 'fas fa-battery-half', 'fas fa-battery-empty', 'fas fa-plug',
        'fas fa-lock', 'fas fa-unlock', 'fas fa-key', 'fas fa-shield-alt', 'fas fa-eye',
        'fas fa-eye-slash', 'fas fa-user-secret', 'fas fa-user-shield', 'fas fa-user-check',
        'fas fa-user-times', 'fas fa-users', 'fas fa-user-friends', 'fas fa-user-plus',
        'fas fa-user-minus', 'fas fa-user-edit', 'fas fa-user-cog', 'fas fa-user-lock',
        'fas fa-shopping-bag', 'fas fa-shopping-basket', 'fas fa-credit-card', 'fas fa-money-bill',
        'fas fa-coins', 'fas fa-wallet', 'fas fa-receipt', 'fas fa-ticket-alt', 'fas fa-qrcode',
        'fas fa-barcode', 'fas fa-percentage', 'fas fa-calculator', 'fas fa-chart-line',
        'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-chart-area', 'fas fa-table',
        'fas fa-list', 'fas fa-list-ol', 'fas fa-list-ul', 'fas fa-th-list', 'fas fa-th',
        'fas fa-th-large', 'fas fa-th-small', 'fas fa-grip-horizontal', 'fas fa-grip-vertical',
        'fas fa-align-left', 'fas fa-align-center', 'fas fa-align-right', 'fas fa-align-justify',
        'fas fa-bold', 'fas fa-italic', 'fas fa-underline', 'fas fa-strikethrough',
        'fas fa-text-height', 'fas fa-text-width', 'fas fa-font', 'fas fa-quote-left',
        'fas fa-quote-right', 'fas fa-paragraph', 'fas fa-indent', 'fas fa-outdent',
        'fas fa-cut', 'fas fa-copy', 'fas fa-paste', 'fas fa-save', 'fas fa-file-download',
        'fas fa-file-upload', 'fas fa-file-export', 'fas fa-file-import', 'fas fa-file-alt',
        'fas fa-file-pdf', 'fas fa-file-word', 'fas fa-file-excel', 'fas fa-file-powerpoint',
        'fas fa-file-image', 'fas fa-file-video', 'fas fa-file-audio', 'fas fa-file-archive',
        'fas fa-file-code', 'fas fa-file-csv', 'fas fa-file-invoice', 'fas fa-file-invoice-dollar',
        'fas fa-file-medical', 'fas fa-file-prescription', 'fas fa-file-signature',
        'fas fa-file-contract', 'fas fa-file-invoice', 'fas fa-file-export', 'fas fa-file-import',
        'fas fa-print', 'fas fa-print-slash', 'fas fa-print-search', 'fas fa-print-area',
        'fas fa-desktop', 'fas fa-laptop', 'fas fa-tablet-alt', 'fas fa-mobile-alt',
        'fas fa-tv', 'fas fa-radio', 'fas fa-headphones', 'fas fa-headset', 'fas fa-keyboard',
        'fas fa-mouse', 'fas fa-mouse-pointer', 'fas fa-hand-pointer', 'fas fa-hand-paper',
        'fas fa-hand-rock', 'fas fa-hand-scissors', 'fas fa-hand-lizard', 'fas fa-hand-spock',
        'fas fa-hand-holding', 'fas fa-hand-holding-heart', 'fas fa-hand-holding-usd',
        'fas fa-hand-holding-water', 'fas fa-hand-holding-medical', 'fas fa-hands',
        'fas fa-hands-helping', 'fas fa-hands-wash', 'fas fa-hands-sanitizer',
        'fas fa-hands-praying', 'fas fa-handshake', 'fas fa-handshake-alt-slash',
        'fas fa-handshake-slash', 'fas fa-handshake-angle', 'fas fa-hand-holding-droplet',
        'fas fa-hand-holding-hand', 'fas fa-hand-holding-box', 'fas fa-hand-holding-seedling',
        'fas fa-hand-holding-heart', 'fas fa-hand-holding-usd', 'fas fa-hand-holding-water',
        'fas fa-hand-holding-medical', 'fas fa-hand-holding-droplet', 'fas fa-hand-holding-hand',
        'fas fa-hand-holding-box', 'fas fa-hand-holding-seedling', 'fas fa-hand-holding-heart',
        'fas fa-hand-holding-usd', 'fas fa-hand-holding-water', 'fas fa-hand-holding-medical'
    ];
    
    function renderIcons(iconsToRender) {
        console.log('Rendering icons:', iconsToRender.length);
        $('#iconLoading').hide();
        iconGrid.empty();
        
        if (iconsToRender.length === 0) {
            iconGrid.html('<div class="col-12 text-center text-muted">لا توجد أيقونات</div>');
            return;
        }
        
        // Clear any existing content
        iconGrid.html('');
        
        iconsToRender.forEach((iconClass, index) => {
            const iconDiv = $(`
                <div class="col-md-2 col-sm-3 col-4 mb-3 text-center">
                    <div class="icon-item p-3 border rounded cursor-pointer" data-icon="${iconClass}" style="transition: all 0.3s ease; background: white; border: 1px solid #dee2e6;">
                        <i class="${iconClass} fa-2x mb-2" style="display: block; color: #007bff; font-size: 2rem;"></i>
                        <div class="small text-muted" style="font-size: 0.75rem; color: #6c757d;">${iconClass}</div>
                    </div>
                </div>
            `);
            
            iconDiv.find('.icon-item').on('click', function() {
                console.log('Icon clicked:', iconClass);
                iconInput.val(iconClass);
                iconModal.modal('hide');
                // Update the input field display
                iconInput.trigger('change');
            });
            
            iconGrid.append(iconDiv);
        });
        
        // Force visibility
        iconGrid.css({
            'visibility': 'visible',
            'opacity': '1',
            'display': 'block'
        });
        
        console.log('Icons rendered successfully. Total items:', iconGrid.find('.icon-item').length);
    }
    
    // Initial render
    console.log('Starting initial render with', icons.length, 'icons');
    renderIcons(icons);
    
    // Test function to verify icons are being rendered
    setTimeout(function() {
        const iconItems = $('.icon-item');
        console.log('Icon items found:', iconItems.length);
        if (iconItems.length > 0) {
            console.log('First icon item HTML:', iconItems.first().html());
        }
    }, 1000);
    
    // Search functionality
    iconSearch.on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const filteredIcons = icons.filter(icon => icon.toLowerCase().includes(searchTerm));
        console.log('Search term:', searchTerm, 'Filtered icons:', filteredIcons.length);
        renderIcons(filteredIcons);
    });
    
    // Add hover effects
    $(document).on('mouseenter', '.icon-item', function() {
        $(this).css('background-color', '#f8f9fa');
    });
    
    $(document).on('mouseleave', '.icon-item', function() {
        $(this).css('background-color', '');
    });
    
    // Ensure modal opens correctly
    $('#iconSelectBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Modal trigger clicked');
        
        // Show loading first
        $('#iconLoading').show();
        iconGrid.empty();
        
        // Show modal
        iconModal.modal('show');
    });
    
    // Show loading when modal is shown
    iconModal.on('shown.bs.modal', function() {
        console.log('Modal shown');
        
        // Force re-render icons when modal is shown
        setTimeout(function() {
            console.log('Rendering icons after modal shown');
            renderIcons(icons);
        }, 200);
    });
    
    // Hide loading when modal is hidden
    iconModal.on('hidden.bs.modal', function() {
        console.log('Modal hidden');
        $('#iconLoading').hide();
    });
    
    // Additional event to ensure icons are rendered
    iconModal.on('show.bs.modal', function() {
        console.log('Modal is about to show');
    });
    
    // Simple icon selection as fallback
    $('#simpleIconSelect').on('click', function() {
        const commonIcons = [
            'fas fa-shopping-cart', 'fas fa-tags', 'fas fa-box', 'fas fa-gift', 'fas fa-star',
            'fas fa-heart', 'fas fa-home', 'fas fa-user', 'fas fa-cog', 'fas fa-search',
            'fas fa-phone', 'fas fa-envelope', 'fas fa-map-marker-alt', 'fas fa-clock',
            'fas fa-calendar', 'fas fa-bell', 'fas fa-bookmark', 'fas fa-thumbs-up',
            'fas fa-share', 'fas fa-download', 'fas fa-upload', 'fas fa-edit', 'fas fa-trash',
            'fas fa-plus', 'fas fa-minus', 'fas fa-check', 'fas fa-times', 'fas fa-arrow-right',
            'fas fa-arrow-left', 'fas fa-arrow-up', 'fas fa-arrow-down', 'fas fa-expand',
            'fas fa-compress', 'fas fa-refresh', 'fas fa-sync', 'fas fa-redo', 'fas fa-undo'
        ];
        
        // Render simple icons
        const simpleGrid = $('#simpleIconGrid');
        simpleGrid.empty();
        
        commonIcons.forEach(iconClass => {
            const iconDiv = $(`
                <div class="col-md-3 col-sm-4 col-6 mb-3 text-center">
                    <div class="simple-icon-item p-2 border rounded cursor-pointer" data-icon="${iconClass}" style="background: white; border: 1px solid #dee2e6; cursor: pointer;">
                        <i class="${iconClass} fa-2x mb-1" style="color: #007bff;"></i>
                        <div class="small text-muted">${iconClass}</div>
                    </div>
                </div>
            `);
            
            iconDiv.find('.simple-icon-item').on('click', function() {
                console.log('Simple icon clicked:', iconClass);
                iconInput.val(iconClass);
                $('#simpleIconModal').modal('hide');
            });
            
            simpleGrid.append(iconDiv);
        });
        
        // Show simple modal
        $('#simpleIconModal').modal('show');
    });
});

// Fallback vanilla JavaScript implementation
document.addEventListener('DOMContentLoaded', function() {
    // Only run if jQuery is not available
    if (typeof $ === 'undefined') {
        console.log('Using vanilla JavaScript fallback for icon selection');
        
        const iconModal = document.getElementById('iconModal');
        const iconInput = document.getElementById('icon');
        const iconGrid = document.getElementById('iconGrid');
        const iconSearch = document.getElementById('iconSearch');
        
        if (!iconModal || !iconInput || !iconGrid || !iconSearch) {
            console.error('Required elements not found for icon selection');
            return;
        }
        
        // Test Font Awesome loading
        console.log('Vanilla JS: Font Awesome loaded:', typeof FontAwesome !== 'undefined');
        console.log('Vanilla JS: Bootstrap loaded:', typeof bootstrap !== 'undefined' ? 'Loaded' : 'Not loaded');
        
        // Popular Font Awesome icons
        const icons = [
            'fas fa-shopping-cart', 'fas fa-tags', 'fas fa-box', 'fas fa-gift', 'fas fa-star',
            'fas fa-heart', 'fas fa-home', 'fas fa-user', 'fas fa-cog', 'fas fa-search',
            'fas fa-phone', 'fas fa-envelope', 'fas fa-map-marker-alt', 'fas fa-clock',
            'fas fa-calendar', 'fas fa-bell', 'fas fa-bookmark', 'fas fa-thumbs-up',
            'fas fa-share', 'fas fa-download', 'fas fa-upload', 'fas fa-edit', 'fas fa-trash',
            'fas fa-plus', 'fas fa-minus', 'fas fa-check', 'fas fa-times', 'fas fa-arrow-right',
            'fas fa-arrow-left', 'fas fa-arrow-up', 'fas fa-arrow-down', 'fas fa-expand',
            'fas fa-compress', 'fas fa-refresh', 'fas fa-sync', 'fas fa-redo', 'fas fa-undo'
        ];
        
        function renderIcons(iconsToRender) {
            console.log('Vanilla JS: Rendering icons:', iconsToRender.length);
            const iconLoading = document.getElementById('iconLoading');
            if (iconLoading) iconLoading.style.display = 'none';
            iconGrid.innerHTML = '';
            
            if (iconsToRender.length === 0) {
                iconGrid.innerHTML = '<div class="col-12 text-center text-muted">لا توجد أيقونات</div>';
                return;
            }
            
            iconsToRender.forEach(iconClass => {
                const iconDiv = document.createElement('div');
                iconDiv.className = 'col-md-2 col-sm-3 col-4 mb-3 text-center';
                iconDiv.innerHTML = `
                    <div class="icon-item p-3 border rounded cursor-pointer" data-icon="${iconClass}" style="transition: all 0.3s ease;">
                        <i class="${iconClass} fa-2x mb-2" style="display: block; color: #007bff;"></i>
                        <div class="small text-muted">${iconClass}</div>
                    </div>
                `;
                
                iconDiv.querySelector('.icon-item').addEventListener('click', function() {
                    console.log('Vanilla JS: Icon clicked:', iconClass);
                    iconInput.value = iconClass;
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = bootstrap.Modal.getInstance(iconModal);
                        if (modal) {
                            modal.hide();
                        }
                    }
                });
                
                iconGrid.appendChild(iconDiv);
            });
        }
        
        // Initial render
        console.log('Vanilla JS: Starting initial render with', icons.length, 'icons');
        renderIcons(icons);
        
        // Test function to verify icons are being rendered
        setTimeout(function() {
            const iconItems = document.querySelectorAll('.icon-item');
            console.log('Vanilla JS: Icon items found:', iconItems.length);
            if (iconItems.length > 0) {
                console.log('Vanilla JS: First icon item HTML:', iconItems[0].innerHTML);
            }
        }, 1000);
        
        // Search functionality
        iconSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredIcons = icons.filter(icon => icon.toLowerCase().includes(searchTerm));
            console.log('Vanilla JS: Search term:', searchTerm, 'Filtered icons:', filteredIcons.length);
            renderIcons(filteredIcons);
        });
        
        // Modal trigger
        const modalTrigger = document.getElementById('iconSelectBtn');
        if (modalTrigger) {
            modalTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Vanilla JS: Modal trigger clicked');
                const iconLoading = document.getElementById('iconLoading');
                if (iconLoading) iconLoading.style.display = 'block';
                
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(iconModal);
                    modal.show();
                    
                    // Hide loading when modal is shown
                    iconModal.addEventListener('shown.bs.modal', function() {
                        console.log('Vanilla JS: Modal shown, hiding loading');
                        if (iconLoading) iconLoading.style.display = 'none';
                    }, { once: true });
                }
            });
        }
    }
});
</script>

<!-- Icon Picker Simple Library -->
<script src="{{ asset('js/icon-picker-simple.js') }}"></script>
@endpush
