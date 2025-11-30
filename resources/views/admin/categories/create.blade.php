@extends('admin.layouts.app')

@section('title', 'إضافة قسم جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="shadow-sm card">
                <div class="text-white card-header bg-primary">
                    <h3 class="mb-0 card-title">
                        <i class="fas fa-plus-circle me-2"></i>إضافة قسم جديد
                    </h3>
                </div>
                <div class="p-4 card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="mb-4 row">
                            <div class="col-12">
                                <h5 class="pb-2 mb-3 text-primary border-bottom">
                                    <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                                </h5>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="name_ar" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>الاسم (عربي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                                           placeholder="أدخل اسم القسم بالعربية" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="name_en" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>الاسم (إنجليزي) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name_en') is-invalid @enderror" 
                                           id="name_en" name="name_en" value="{{ old('name_en') }}" 
                                           placeholder="Enter category name in English" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="description_ar" class="form-label fw-bold">
                                        <i class="fas fa-align-right me-1"></i>الوصف (عربي)
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" name="description_ar" rows="4" 
                                              placeholder="أدخل وصف القسم بالعربية">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="description_en" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1"></i>الوصف (إنجليزي)
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" name="description_en" rows="4" 
                                              placeholder="Enter category description in English">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media Section -->
                        <div class="mb-4 row">
                            <div class="col-12">
                                <h5 class="pb-2 mb-3 text-primary border-bottom">
                                    <i class="fas fa-images me-2"></i>الصور والأيقونات
                                </h5>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="image" class="form-label fw-bold">
                                        <i class="fas fa-image me-1"></i>صورة القسم
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
                                <div class="mb-3 form-group">
                                    <label for="icon" class="form-label fw-bold">
                                        <i class="fas fa-icons me-1"></i>أيقونة القسم
                                    </label>
                                    <div class="input-group">
                                        <span class="text-white input-group-text bg-primary" id="icon-preview">
                                            <i class="fas fa-icons"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon') }}" 
                                               placeholder="مثال: fas fa-shopping-cart" readonly>
                                        <button type="button" class="btn btn-primary" onclick="openIconPicker('icon', 'icon-preview')">
                                            <i class="fas fa-search"></i> اختيار
                                        </button>
                                    </div>
                                    <div class="form-text">اضغط على زر "اختيار" لفتح مكتبة 300+ أيقونة Font Awesome</div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="mb-4 row">
                            <div class="col-12">
                                <h5 class="pb-2 mb-3 text-primary border-bottom">
                                    <i class="fas fa-cog me-2"></i>الإعدادات
                                </h5>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="sort_order" class="form-label fw-bold">
                                        <i class="fas fa-sort-numeric-up me-1"></i>ترتيب العرض
                                    </label>
                                    <input type="number" class="form-control form-control-lg @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                           min="0" placeholder="0">
                                    <div class="form-text">كلما قل الرقم، كلما ظهر القسم في المقدمة</div>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <div class="mt-4 form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <i class="fas fa-toggle-on me-1"></i>القسم نشط
                                        </label>
                                    </div>
                                    <div class="form-text">القسم غير النشط لن يظهر في الموقع</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="gap-3 d-flex justify-content-end">
                                    <button type="submit" class="px-4 btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>حفظ القسم
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}" class="px-4 btn btn-secondary btn-lg">
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
                    <div class="mb-3 col-md-12">
                        <input type="text" class="form-control" id="iconSearch" placeholder="البحث عن أيقونة...">
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

@push('styles')
<style>
    /* Ensure Font Awesome icons display properly in modal */
    #iconModal .fas, #iconModal .fa, #iconModal .far, #iconModal .fab {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
        font-weight: 900 !important;
        font-style: normal !important;
    }
    
    #iconModal .far {
        font-weight: 400 !important;
    }
    
    #iconModal .fab {
        font-family: "Font Awesome 6 Brands" !important;
        font-weight: 400 !important;
    }
    
    /* Icon item styling */
    .icon-item {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6 !important;
    }
    
    .icon-item:hover {
        background-color: #f8f9fa !important;
        border-color: #007bff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .icon-item i {
        display: block !important;
        color: #007bff !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Fallback for when jQuery is not loaded
if (typeof $ === 'undefined') {
    console.error('jQuery is not loaded. Icon selection may not work properly.');
}

$(document).ready(function() {
    // Icon selection functionality
    const iconModal = $('#iconModal');
    const iconInput = $('#icon');
    const iconGrid = $('#iconGrid');
    const iconSearch = $('#iconSearch');
    
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
        iconGrid.empty();
        
        if (iconsToRender.length === 0) {
            iconGrid.html('<div class="text-center col-12 text-muted">لا توجد أيقونات</div>');
            return;
        }
        
        iconsToRender.forEach(iconClass => {
            const iconDiv = $(`
                <div class="mb-3 text-center col-md-2 col-sm-3 col-4">
                    <div class="p-3 border rounded cursor-pointer icon-item" data-icon="${iconClass}" style="transition: all 0.3s ease;">
                        <i class="${iconClass} fa-2x mb-2" style="display: block; color: #007bff;"></i>
                        <div class="small text-muted">${iconClass}</div>
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
    }
    
    // Initial render
    renderIcons(icons);
    
    // Search functionality
    iconSearch.on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const filteredIcons = icons.filter(icon => icon.toLowerCase().includes(searchTerm));
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
    $('[data-bs-toggle="modal"][data-bs-target="#iconModal"]').on('click', function(e) {
        e.preventDefault();
        iconModal.modal('show');
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
            iconGrid.innerHTML = '';
            iconsToRender.forEach(iconClass => {
                const iconDiv = document.createElement('div');
                iconDiv.className = 'col-md-2 col-sm-3 col-4 mb-3 text-center';
                iconDiv.innerHTML = `
                    <div class="p-3 border rounded cursor-pointer icon-item" data-icon="${iconClass}">
                        <i class="${iconClass} fa-2x mb-2"></i>
                        <div class="small text-muted">${iconClass}</div>
                    </div>
                `;
                
                iconDiv.querySelector('.icon-item').addEventListener('click', function() {
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
        renderIcons(icons);
        
        // Search functionality
        iconSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredIcons = icons.filter(icon => icon.toLowerCase().includes(searchTerm));
            renderIcons(filteredIcons);
        });
        
        // Modal trigger
        const modalTrigger = document.querySelector('[data-bs-toggle="modal"][data-bs-target="#iconModal"]');
        if (modalTrigger) {
            modalTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(iconModal);
                    modal.show();
                }
            });
        }
    }
});
</script>

<!-- Icon Picker Simple Library -->
<script src="{{ asset('js/icon-picker-simple.js') }}"></script>
@endpush
