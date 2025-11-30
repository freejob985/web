@extends('admin.layouts.app')

@section('title', 'إضافة كوبون جديد')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="fas fa-plus-circle me-3 text-primary"></i>إضافة كوبون جديد</h1>
            <p class="text-muted mb-0">قم بإنشاء كوبون خصم جديد مع تحديد جميع الإعدادات المطلوبة</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
            </a>
            <button type="button" class="btn btn-info" onclick="showCouponGuide()">
                <i class="fas fa-question-circle me-2"></i>دليل الإنشاء
            </button>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>يرجى تصحيح الأخطاء التالية:</strong>
        </div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Progress Indicator -->
    <div class="progress-container mb-4">
        <div class="progress-steps">
            <div class="progress-step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">المعلومات الأساسية</div>
            </div>
            <div class="progress-step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">إعدادات الخصم</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">قيود الاستخدام</div>
            </div>
            <div class="progress-step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">نطاق التطبيق</div>
            </div>
            <div class="progress-step" data-step="5">
                <div class="step-number">5</div>
                <div class="step-label">التوقيت والحالة</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
        @csrf
        
        <!-- Section 1: Basic Information -->
        <div class="form-section active" id="section-1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="section-title">
                        <h4>المعلومات الأساسية</h4>
                        <p class="section-description">أدخل المعلومات الأساسية للكوبون مثل الكود والاسم والوصف</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-tag me-1"></i>كود الكوبون
                            </label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" required 
                                   placeholder="مثال: SAVE20">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">كود فريد يستخدمه العملاء لتطبيق الخصم</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-signature me-1"></i>اسم الكوبون
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="مثال: خصم 20% على جميع المنتجات">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">اسم وصفي للكوبون يظهر في لوحة الإدارة</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-left me-1"></i>الوصف
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="وصف تفصيلي عن الكوبون وشروط استخدامه">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">وصف اختياري يساعد في فهم الغرض من الكوبون</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Discount Settings -->
        <div class="form-section" id="section-2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="section-title">
                        <h4>إعدادات الخصم</h4>
                        <p class="section-description">حدد نوع الخصم وقيمته والحدود المطبقة</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-cogs me-1"></i>نوع الخصم
                            </label>
                            <select class="form-control @error('discount_type') is-invalid @enderror" 
                                    id="discount_type" name="discount_type" required>
                                <option value="">اختر النوع</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">اختر بين خصم بمبلغ ثابت أو نسبة مئوية</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-calculator me-1"></i>قيمة الخصم
                            </label>
                            <input type="number" class="form-control @error('discount_value') is-invalid @enderror" 
                                   id="discount_value" name="discount_value" value="{{ old('discount_value') }}" 
                                   step="0.001" min="0" required placeholder="0.000">
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">قيمة الخصم (دينار للمبلغ الثابت أو % للنسبة)</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-arrow-up me-1"></i>الحد الأدنى للطلب
                            </label>
                            <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror" 
                                   id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount') }}" 
                                   step="0.001" min="0" placeholder="0.000">
                            @error('minimum_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">أقل مبلغ للطلب لتطبيق الكوبون (اختياري)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-arrow-down me-1"></i>الحد الأقصى للخصم
                            </label>
                            <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror" 
                                   id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount') }}" 
                                   step="0.001" min="0" placeholder="0.000">
                            @error('maximum_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">أقصى مبلغ خصم (مفيد للنسب المئوية)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-toggle-on me-1"></i>حالة الكوبون
                            </label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">حالة الكوبون الحالية</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Usage Limits -->
        <div class="form-section" id="section-3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-limit"></i>
                    </div>
                    <div class="section-title">
                        <h4>قيود الاستخدام</h4>
                        <p class="section-description">حدد عدد مرات الاستخدام المسموحة للكوبون</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-infinity me-1"></i>نوع حد الاستخدام
                            </label>
                            <select class="form-control @error('usage_limit_type') is-invalid @enderror" 
                                    id="usage_limit_type" name="usage_limit_type" required>
                                <option value="">اختر النوع</option>
                                <option value="unlimited" {{ old('usage_limit_type') == 'unlimited' ? 'selected' : '' }}>غير محدود</option>
                                <option value="limited" {{ old('usage_limit_type') == 'limited' ? 'selected' : '' }}>محدود</option>
                            </select>
                            @error('usage_limit_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">هل يمكن استخدام الكوبون عدد لا نهائي من المرات؟</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="usage_limit_group" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>حد الاستخدام
                                </label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                                       min="1" placeholder="100">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">عدد مرات الاستخدام المسموحة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Application Scope -->
        <div class="form-section" id="section-4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="section-title">
                        <h4>نطاق التطبيق</h4>
                        <p class="section-description">حدد المنتجات أو الأقسام التي ينطبق عليها الكوبون</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-target me-1"></i>نوع التطبيق
                            </label>
                            <select class="form-control @error('applicable_type') is-invalid @enderror" 
                                    id="applicable_type" name="applicable_type" required>
                                <option value="">اختر النوع</option>
                                <option value="all_products" {{ old('applicable_type') == 'all_products' ? 'selected' : '' }}>جميع المنتجات</option>
                                <option value="specific_products" {{ old('applicable_type') == 'specific_products' ? 'selected' : '' }}>منتجات محددة</option>
                                <option value="specific_categories" {{ old('applicable_type') == 'specific_categories' ? 'selected' : '' }}>أقسام محددة</option>
                            </select>
                            @error('applicable_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">اختر نطاق تطبيق الكوبون</small>
                        </div>
                        <div class="col-md-6"></div>
                        
                        <!-- Specific Products -->
                        <div class="col-12 mb-3" id="specific_products_group" style="display: none;">
                            <label class="form-label">
                                <i class="fas fa-box me-1"></i>المنتجات المحددة
                            </label>
                            <select class="form-control @error('applicable_products') is-invalid @enderror" 
                                    id="applicable_products" name="applicable_products[]" multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            {{ in_array($product->id, old('applicable_products', [])) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('applicable_products')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">اختر المنتجات التي ينطبق عليها الكوبون (يمكن اختيار أكثر من منتج)</small>
                        </div>

                        <!-- Specific Categories -->
                        <div class="col-12 mb-3" id="specific_categories_group" style="display: none;">
                            <label class="form-label">
                                <i class="fas fa-layer-group me-1"></i>الأقسام المحددة
                            </label>
                            <select class="form-control @error('applicable_categories') is-invalid @enderror" 
                                    id="applicable_categories" name="applicable_categories[]" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ in_array($category->id, old('applicable_categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('applicable_categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">اختر الأقسام التي ينطبق عليها الكوبون (يمكن اختيار أكثر من قسم)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Timing and Status -->
        <div class="form-section" id="section-5">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="section-title">
                        <h4>التوقيت والحالة النهائية</h4>
                        <p class="section-description">حدد فترة صلاحية الكوبون والإعدادات النهائية</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-play me-1"></i>تاريخ البداية
                            </label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">تاريخ بداية صلاحية الكوبون (اختياري)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-stop me-1"></i>تاريخ الانتهاء
                            </label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">تاريخ انتهاء صلاحية الكوبون (اختياري)</small>
                        </div>
                    </div>
                    
                    <!-- Summary Section -->
                    <div class="coupon-summary mt-4">
                        <h6><i class="fas fa-clipboard-list me-2"></i>ملخص الكوبون</h6>
                        <div class="summary-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="summary-item">
                                        <strong>الكود:</strong> <span id="summary-code">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <strong>نوع الخصم:</strong> <span id="summary-type">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <strong>قيمة الخصم:</strong> <span id="summary-value">-</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="summary-item">
                                        <strong>الحد الأدنى:</strong> <span id="summary-minimum">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <strong>حد الاستخدام:</strong> <span id="summary-usage">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <strong>نطاق التطبيق:</strong> <span id="summary-scope">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="form-navigation">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                    <i class="fas fa-arrow-right me-2"></i>السابق
                </button>
                <div class="step-indicator">
                    <span id="stepText">الخطوة 1 من 5</span>
                </div>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                    التالي<i class="fas fa-arrow-left ms-2"></i>
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                    <i class="fas fa-save me-2"></i>حفظ الكوبون
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* Progress Steps */
.progress-container {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 50px;
    right: 50px;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 15px;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.step-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-align: center;
    transition: all 0.3s ease;
}

.progress-step.active .step-number {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    transform: scale(1.1);
}

.progress-step.active .step-label {
    color: #3498db;
}

.progress-step.completed .step-number {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: white;
}

.progress-step.completed .step-label {
    color: #27ae60;
}

/* Form Sections */
.form-section {
    display: none;
    animation: fadeIn 0.5s ease-in-out;
}

.form-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 25px 30px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    gap: 20px;
}

.section-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.section-title h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-weight: 700;
}

.section-description {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.section-body {
    padding: 30px;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-label.required::after {
    content: '*';
    color: #e74c3c;
    margin-right: 5px;
    font-weight: 700;
}

.form-label i {
    color: #3498db;
    font-size: 0.9rem;
    margin-left: 8px;
    width: 16px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 12px 16px;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
    transform: translateY(-1px);
}

.form-text {
    font-size: 0.8rem;
    margin-top: 5px;
    color: #6c757d;
}

/* Coupon Summary */
.coupon-summary {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 15px;
    padding: 20px;
    border: 2px solid #dee2e6;
}

.coupon-summary h6 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 15px;
}

.summary-content {
    background: white;
    border-radius: 10px;
    padding: 15px;
}

.summary-item {
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f1f3f4;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item strong {
    color: #495057;
    font-size: 0.9rem;
}

.summary-item span {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Navigation */
.form-navigation {
    background: white;
    border-radius: 15px;
    padding: 25px 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-top: 30px;
}

.step-indicator {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 12px 25px;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.btn-success {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #138496);
}

/* Responsive Design */
@media (max-width: 768px) {
    .progress-steps {
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .progress-steps::before {
        display: none;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .section-body {
        padding: 20px;
    }
    
    .form-navigation .d-flex {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
let currentStep = 1;
const totalSteps = 5;

document.addEventListener('DOMContentLoaded', function() {
    // Original functionality
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
        updateSummary();
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
        updateSummary();
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

    // Add event listeners for summary updates
    document.getElementById('code').addEventListener('input', updateSummary);
    document.getElementById('discount_type').addEventListener('change', updateSummary);
    document.getElementById('discount_value').addEventListener('input', updateSummary);
    document.getElementById('minimum_amount').addEventListener('input', updateSummary);

    // Initialize form
    updateStepDisplay();
    updateSummary();
});

function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep <script 1 || newStep > totalSteps) {
        return;
    }
    
    // Validate current step before moving forward
    if (direction > 0 && !validateStep(currentStep)) {
        return;
    }
    
    // Hide current step
    document.getElementById(`section-${currentStep}`).classList.remove('active');
    document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
    
    // Mark completed steps
    if (direction > 0) {
        document.querySelector(`[data-step="${currentStep}"]`).classList.add('completed');
    }
    
    // Show new step
    currentStep = newStep;
    document.getElementById(`section-${currentStep}`).classList.add('active');
    document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
    
    updateStepDisplay();
    updateSummary();
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepDisplay() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const stepText = document.getElementById('stepText');
    
    // Update step text
    stepText.textContent = `الخطوة ${currentStep} من ${totalSteps}`;
    
    // Show/hide navigation buttons
    prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-block';
    nextBtn.style.display = currentStep === totalSteps ? 'none' : 'inline-block';
    submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
}

function validateStep(step) {
    const section = document.getElementById(`section-${step}`);
    const requiredFields = section.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
            
            // Remove invalid class after user starts typing
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            }, { once: true });
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
    }
    
    return isValid;
}

function updateSummary() {
    const code = document.getElementById('code').value || '-';
    const discountType = document.getElementById('discount_type').value;
    const discountValue = document.getElementById('discount_value').value;
    const minimumAmount = document.getElementById('minimum_amount').value;
    const usageLimitType = document.getElementById('usage_limit_type').value;
    const usageLimit = document.getElementById('usage_limit').value;
    const applicableType = document.getElementById('applicable_type').value;
    
    // Update summary fields
    document.getElementById('summary-code').textContent = code;
    
    let typeText = '-';
    if (discountType === 'fixed') typeText = 'مبلغ ثابت';
    else if (discountType === 'percentage') typeText = 'نسبة مئوية';
    document.getElementById('summary-type').textContent = typeText;
    
    let valueText = '-';
    if (discountValue) {
        valueText = discountValue + (discountType === 'percentage' ? '%' : ' دينار');
    }
    document.getElementById('summary-value').textContent = valueText;
    
    document.getElementById('summary-minimum').textContent = minimumAmount ? minimumAmount + ' دينار' : 'لا يوجد';
    
    let usageText = '-';
    if (usageLimitType === 'unlimited') usageText = 'غير محدود';
    else if (usageLimitType === 'limited' && usageLimit) usageText = usageLimit + ' مرة';
    document.getElementById('summary-usage').textContent = usageText;
    
    let scopeText = '-';
    if (applicableType === 'all_products') scopeText = 'جميع المنتجات';
    else if (applicableType === 'specific_products') scopeText = 'منتجات محددة';
    else if (applicableType === 'specific_categories') scopeText = 'أقسام محددة';
    document.getElementById('summary-scope').textContent = scopeText;
}

function showCouponGuide() {
    const guideModal = `
        <div class="modal fade" id="couponGuideModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-question-circle me-2"></i>دليل إنشاء الكوبونات
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="guide-section">
                            <h6><i class="fas fa-info-circle me-2 text-primary"></i>المعلومات الأساسية</h6>
                            <ul>
                                <li>اختر كود فريد وسهل التذكر للكوبون</li>
                                <li>أضف اسم وصفي يوضح الغرض من الكوبون</li>
                                <li>اكتب وصف تفصيلي عن شروط الاستخدام</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-percentage me-2 text-info"></i>إعدادات الخصم</h6>
                            <ul>
                                <li><strong>مبلغ ثابت:</strong> خصم بمبلغ محدد بالدينار</li>
                                <li><strong>نسبة مئوية:</strong> خصم بنسبة من قيمة الطلب</li>
                                <li>حدد الحد الأدنى لقيمة الطلب إن أردت</li>
                                <li>اختر الحد الأقصى للخصم للنسب المئوية</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-limit me-2 text-warning"></i>قيود الاستخدام</h6>
                            <ul>
                                <li><strong>غير محدود:</strong> يمكن استخدامه عدد لا نهائي من المرات</li>
                                <li><strong>محدود:</strong> حدد عدد مرات الاستخدام المسموحة</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-bullseye me-2 text-success"></i>نطاق التطبيق</h6>
                            <ul>
                                <li><strong>جميع المنتجات:</strong> ينطبق على كامل المتجر</li>
                                <li><strong>منتجات محددة:</strong> اختر منتجات معينة</li>
                                <li><strong>أقسام محددة:</strong> اختر أقسام معينة</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-clock me-2 text-secondary"></i>التوقيت</h6>
                            <ul>
                                <li>حدد تاريخ بداية الكوبون (اختياري)</li>
                                <li>حدد تاريخ انتهاء الكوبون (اختياري)</li>
                                <li>راجع الملخص قبل الحفظ</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('couponGuideModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', guideModal);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('couponGuideModal'));
    modal.show();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add guide modal styles
const guideStyles = `
<style>
.guide-section {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.guide-section h6 {
    margin-bottom: 10px;
    font-weight: 600;
}

.guide-section ul {
    margin-bottom: 0;
    padding-right: 20px;
}

.guide-section li {
    margin-bottom: 5px;
    color: #6c757d;
}

.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', guideStyles);
</script>
@endsection
