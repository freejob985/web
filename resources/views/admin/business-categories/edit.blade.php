@extends('admin.layouts.app')

@section('title', 'تعديل فئة الأعمال')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>تعديل فئة الأعمال: {{ $businessCategory->name }}
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.business-categories.update', $businessCategory) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                           id="name" name="name" value="{{ old('name', $businessCategory->name) }}" 
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
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $businessCategory->sort_order) }}" 
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
                                              placeholder="أدخل وصف مختصر عن فئة الأعمال">{{ old('description', $businessCategory->description) }}</textarea>
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
                                               id="icon" name="icon" value="{{ old('icon', $businessCategory->icon) }}" 
                                               placeholder="مثال: fas fa-briefcase" readonly>
                                        <span class="input-group-text bg-white" id="icon-preview">
                                            @if($businessCategory->icon)
                                                <i class="{{ $businessCategory->icon }}"></i>
                                            @else
                                                <i class="fas fa-icons"></i>
                                            @endif
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
                                               value="1" {{ old('is_active', $businessCategory->is_active) ? 'checked' : '' }}>
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
                                    <button type="submit" class="btn btn-info btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>حفظ التغييرات
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
                        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Icon selection functionality
    const iconModal = $('#iconModal');
    const iconInput = $('#icon');
    const iconGrid = $('#iconGrid');
    const iconSearch = $('#iconSearch');
    
    // Popular Font Awesome icons for business categories
    const icons = [
        'fas fa-utensils', 'fas fa-store', 'fas fa-shopping-cart', 'fas fa-tags', 'fas fa-box',
        'fas fa-gift', 'fas fa-star', 'fas fa-heart', 'fas fa-home', 'fas fa-user',
        'fas fa-cog', 'fas fa-search', 'fas fa-phone', 'fas fa-envelope', 'fas fa-map-marker-alt',
        'fas fa-clock', 'fas fa-calendar', 'fas fa-bell', 'fas fa-bookmark', 'fas fa-thumbs-up',
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
        iconGrid.empty();
        iconsToRender.forEach(iconClass => {
            const iconDiv = $(`
                <div class="col-md-2 col-sm-3 col-4 mb-3 text-center">
                    <div class="icon-item p-3 border rounded cursor-pointer" data-icon="${iconClass}">
                        <i class="${iconClass} fa-2x mb-2"></i>
                        <div class="small text-muted">${iconClass}</div>
                    </div>
                </div>
            `);
            
            iconDiv.find('.icon-item').on('click', function() {
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
</script>

<!-- Icon Picker Simple Library -->
<script src="{{ asset('js/icon-picker-simple.js') }}"></script>
@endpush