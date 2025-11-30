@extends('admin.layouts.app')

@section('title', 'تعديل قناة الدعم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل قناة الدعم</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.support-channels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.support-channels.update', $supportChannel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_ar">العنوان بالعربية <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" 
                                           id="title_ar" name="title_ar" value="{{ old('title_ar', $supportChannel->title_ar) }}" required>
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_en">العنوان بالإنجليزية</label>
                                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                                           id="title_en" name="title_en" value="{{ old('title_en', $supportChannel->title_en) }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar">الوصف بالعربية <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" name="description_ar" rows="3" required>{{ old('description_ar', $supportChannel->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en">الوصف بالإنجليزية</label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" name="description_en" rows="3">{{ old('description_en', $supportChannel->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_info">معلومات التواصل <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_info') is-invalid @enderror" 
                                           id="contact_info" name="contact_info" value="{{ old('contact_info', $supportChannel->contact_info) }}" 
                                           placeholder="رقم الهاتف أو الإيميل" required>
                                    @error('contact_info')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="availability">ساعات العمل <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('availability') is-invalid @enderror" 
                                           id="availability" name="availability" value="{{ old('availability', $supportChannel->availability) }}" 
                                           placeholder="مثال: 24/7 أو 9 ص - 5 م" required>
                                    @error('availability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="icon">الأيقونة</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $supportChannel->icon) }}" 
                                           placeholder="مثال: fas fa-headset">
                                    <small class="form-text text-muted">استخدم Font Awesome icons</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">اللون</label>
                                    <input type="color" class="form-control @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $supportChannel->color) }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $supportChannel->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $supportChannel->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    قناة مفعلة
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.support-channels.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Preview icon
    function updateIconPreview() {
        const iconClass = $('#icon').val();
        const color = $('#color').val();
        
        $('#icon-preview').remove();
        if (iconClass) {
            $('#icon').after(`<div id="icon-preview" class="mt-2"><i class="${iconClass} fa-2x" style="color: ${color}"></i></div>`);
        }
    }

    // Initial preview
    updateIconPreview();

    // Update preview when icon changes
    $('#icon').on('input', updateIconPreview);

    // Update preview when color changes
    $('#color').on('change', updateIconPreview);
});
</script>
@endpush
