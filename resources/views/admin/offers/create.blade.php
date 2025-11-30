@extends('admin.layouts.app')

@section('title', 'إضافة عرض جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        إضافة عرض جديد
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">عنوان العرض <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="offer_category_id" class="form-label">قسم العرض <span class="text-danger">*</span></label>
                                    <select class="form-select @error('offer_category_id') is-invalid @enderror" 
                                            id="offer_category_id" name="offer_category_id" required>
                                        <option value="">اختر القسم</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('offer_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('offer_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف العرض</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">صورة العرض</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">الصور المقبولة: JPG, PNG, GIF (حد أقصى 2MB)</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">نوع الخصم</label>
                                    <select class="form-select @error('discount_type') is-invalid @enderror" 
                                            id="discount_type" name="discount_type">
                                        <option value="percentage" {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                        <option value="buy_x_get_y" {{ old('discount_type') == 'buy_x_get_y' ? 'selected' : '' }}>اشتر X واحصل على Y</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="original_price" class="form-label">السعر الأصلي <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('original_price') is-invalid @enderror" 
                                           id="original_price" name="original_price" 
                                           value="{{ old('original_price') }}" required>
                                    @error('original_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="offer_price" class="form-label">سعر العرض <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('offer_price') is-invalid @enderror" 
                                           id="offer_price" name="offer_price" 
                                           value="{{ old('offer_price') }}" required>
                                    @error('offer_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_percentage" class="form-label">نسبة الخصم (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" 
                                           class="form-control @error('discount_percentage') is-invalid @enderror" 
                                           id="discount_percentage" name="discount_percentage" 
                                           value="{{ old('discount_percentage') }}">
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="buy_x_get_y_fields" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="buy_quantity" class="form-label">اشتر (X)</label>
                                    <input type="number" min="1" 
                                           class="form-control @error('buy_quantity') is-invalid @enderror" 
                                           id="buy_quantity" name="buy_quantity" 
                                           value="{{ old('buy_quantity') }}">
                                    @error('buy_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="get_quantity" class="form-label">احصل على (Y)</label>
                                    <input type="number" min="1" 
                                           class="form-control @error('get_quantity') is-invalid @enderror" 
                                           id="get_quantity" name="get_quantity" 
                                           value="{{ old('get_quantity') }}">
                                    @error('get_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">تاريخ البداية</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">تاريخ النهاية</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">ترتيب العرض</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', 0) }}">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    نشط
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                       value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    مميز
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_limited_time" name="is_limited_time" 
                                                       value="1" {{ old('is_limited_time') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_limited_time">
                                                    بيع سريع
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                حفظ العرض
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide buy x get y fields based on discount type
document.getElementById('discount_type').addEventListener('change', function() {
    const buyXGetYFields = document.getElementById('buy_x_get_y_fields');
    if (this.value === 'buy_x_get_y') {
        buyXGetYFields.style.display = 'block';
    } else {
        buyXGetYFields.style.display = 'none';
    }
});

// Calculate discount percentage automatically
function calculateDiscount() {
    const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
    const offerPrice = parseFloat(document.getElementById('offer_price').value) || 0;
    
    if (originalPrice > 0 && offerPrice > 0) {
        const discount = ((originalPrice - offerPrice) / originalPrice) * 100;
        document.getElementById('discount_percentage').value = discount.toFixed(2);
    }
}

document.getElementById('original_price').addEventListener('input', calculateDiscount);
document.getElementById('offer_price').addEventListener('input', calculateDiscount);
</script>
@endsection
