@extends('admin.layouts.app')

@section('title', 'تعديل الكوبون')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل الكوبون: {{ $coupon->name }}</h3>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">كود الكوبون <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">اسم الكوبون <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $coupon->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Settings -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_type">نوع الخصم <span class="text-danger">*</span></label>
                                    <select class="form-control @error('discount_type') is-invalid @enderror" 
                                            id="discount_type" name="discount_type" required>
                                        <option value="">اختر النوع</option>
                                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_value">قيمة الخصم <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                           id="discount_value" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" 
                                           step="0.001" min="0" required>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="minimum_amount">الحد الأدنى للطلب</label>
                                    <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror" 
                                           id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount', $coupon->minimum_amount) }}" 
                                           step="0.001" min="0">
                                    @error('minimum_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maximum_discount">الحد الأقصى للخصم</label>
                                    <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror" 
                                           id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount', $coupon->maximum_discount) }}" 
                                           step="0.001" min="0">
                                    @error('maximum_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="expired" {{ old('status', $coupon->status) == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Usage Limits -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usage_limit_type">نوع حد الاستخدام <span class="text-danger">*</span></label>
                                    <select class="form-control @error('usage_limit_type') is-invalid @enderror" 
                                            id="usage_limit_type" name="usage_limit_type" required>
                                        <option value="">اختر النوع</option>
                                        <option value="unlimited" {{ old('usage_limit_type', $coupon->usage_limit_type) == 'unlimited' ? 'selected' : '' }}>غير محدود</option>
                                        <option value="limited" {{ old('usage_limit_type', $coupon->usage_limit_type) == 'limited' ? 'selected' : '' }}>محدود</option>
                                    </select>
                                    @error('usage_limit_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group" id="usage_limit_group" style="display: none;">
                                    <label for="usage_limit">حد الاستخدام</label>
                                    <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                           id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" 
                                           min="1">
                                    @error('usage_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Applicable Products -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applicable_type">نوع التطبيق <span class="text-danger">*</span></label>
                                    <select class="form-control @error('applicable_type') is-invalid @enderror" 
                                            id="applicable_type" name="applicable_type" required>
                                        <option value="">اختر النوع</option>
                                        <option value="all_products" {{ old('applicable_type', $coupon->applicable_type) == 'all_products' ? 'selected' : '' }}>جميع المنتجات</option>
                                        <option value="specific_products" {{ old('applicable_type', $coupon->applicable_type) == 'specific_products' ? 'selected' : '' }}>منتجات محددة</option>
                                        <option value="specific_categories" {{ old('applicable_type', $coupon->applicable_type) == 'specific_categories' ? 'selected' : '' }}>أقسام محددة</option>
                                    </select>
                                    @error('applicable_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Specific Products -->
                        <div class="form-group" id="specific_products_group" style="display: none;">
                            <label for="applicable_products">المنتجات المحددة</label>
                            <select class="form-control @error('applicable_products') is-invalid @enderror" 
                                    id="applicable_products" name="applicable_products[]" multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ in_array($product->id, old('applicable_products', $coupon->applicable_products ?? [])) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('applicable_products')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specific Categories -->
                        <div class="form-group" id="specific_categories_group" style="display: none;">
                            <label for="applicable_categories">الأقسام المحددة</label>
                            <select class="form-control @error('applicable_categories') is-invalid @enderror" 
                                    id="applicable_categories" name="applicable_categories[]" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ in_array($category->id, old('applicable_categories', $coupon->applicable_categories ?? [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('applicable_categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time Settings -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="starts_at">تاريخ البداية</label>
                                    <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                           id="starts_at" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}">
                                    @error('starts_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expires_at">تاريخ الانتهاء</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">تحديث الكوبون</button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usageLimitType = document.getElementById('usage_limit_type');
    const usageLimitGroup = document.getElementById('usage_limit_group');
    const applicableType = document.getElementById('applicable_type');
    const specificProductsGroup = document.getElementById('specific_products_group');
    const specificCategoriesGroup = document.getElementById('specific_categories_group');

    // Usage limit toggle
    usageLimitType.addEventListener('change', function() {
        if (this.value === 'limited') {
            usageLimitGroup.style.display = 'block';
            document.getElementById('usage_limit').required = true;
        } else {
            usageLimitGroup.style.display = 'none';
            document.getElementById('usage_limit').required = false;
        }
    });

    // Applicable type toggle
    applicableType.addEventListener('change', function() {
        specificProductsGroup.style.display = 'none';
        specificCategoriesGroup.style.display = 'none';
        
        if (this.value === 'specific_products') {
            specificProductsGroup.style.display = 'block';
        } else if (this.value === 'specific_categories') {
            specificCategoriesGroup.style.display = 'block';
        }
    });

    // Initialize on page load
    if (usageLimitType.value === 'limited') {
        usageLimitGroup.style.display = 'block';
    }
    
    if (applicableType.value === 'specific_products') {
        specificProductsGroup.style.display = 'block';
    } else if (applicableType.value === 'specific_categories') {
        specificCategoriesGroup.style.display = 'block';
    }
});
</script>
@endsection
