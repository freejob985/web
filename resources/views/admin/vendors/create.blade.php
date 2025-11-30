@extends('admin.layouts.app')

@section('content')
<style>
/* Fix Layout Issues */
.main-content {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.container-fluid {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: none;
    padding-left: 15px;
    padding-right: 15px;
}

/* Ensure proper positioning */
body {
    overflow-x: hidden;
}

.content-wrapper {
    position: relative;
    width: 100%;
    min-height: 100vh;
}
</style>

<div class="main-content">
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="fas fa-plus-circle me-3 text-primary"></i>إضافة مورد جديد</h1>
            <p class="text-muted mb-0">قم بملء جميع المعلومات المطلوبة لإنشاء حساب مورد جديد</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
            </a>
            <button type="button" class="btn btn-info" onclick="showFormGuide()">
                <i class="fas fa-question-circle me-2"></i>دليل الاستخدام
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
                <div class="step-label">معلومات الاتصال</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">معلومات العمل</div>
            </div>
            <div class="progress-step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">المعلومات المالية</div>
            </div>
            <div class="progress-step" data-step="5">
                <div class="step-number">5</div>
                <div class="step-label">الإعدادات</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.vendors.store') }}" id="vendorForm">
        @csrf
        
        <!-- Section 1: Basic Information -->
        <div class="form-section active" id="section-1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="section-title">
                        <h4>المعلومات الأساسية</h4>
                        <p class="section-description">أدخل المعلومات الأساسية للمورد مثل الاسم والوصف والتصنيف</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-user me-1"></i>اسم المورد (إنجليزي)
                            </label>
                            <input class="form-control" name="name" required placeholder="أدخل اسم المورد بالإنجليزية" />
                            <small class="form-text text-muted">الاسم باللغة الإنجليزية مطلوب للنظام</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1"></i>اسم المورد (عربي)
                            </label>
                            <input class="form-control" name="name_ar" placeholder="أدخل اسم المورد بالعربية" />
                            <small class="form-text text-muted">الاسم باللغة العربية للعرض للمستخدمين</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tags me-1"></i>التصنيف
                            </label>
                            <input class="form-control" name="category" placeholder="تصنيف المورد" />
                            <small class="form-text text-muted">مثال: منتجات زراعية، منتجات ألبان</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-lock me-1"></i>كلمة المرور
                            </label>
                            <input class="form-control" type="password" name="password" required placeholder="كلمة المرور" />
                            <small class="form-text text-muted">كلمة مرور قوية لحماية الحساب</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>الوصف
                            </label>
                            <textarea class="form-control" name="description" rows="3" placeholder="وصف تفصيلي عن المورد ونشاطه"></textarea>
                            <small class="form-text text-muted">وصف مختصر عن نشاط المورد ومنتجاته</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Contact Information -->
        <div class="form-section" id="section-2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <div class="section-title">
                        <h4>معلومات الاتصال</h4>
                        <p class="section-description">أدخل معلومات الاتصال والعنوان الخاص بالمورد</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">
                                <i class="fas fa-envelope me-1"></i>البريد الإلكتروني
                            </label>
                            <input class="form-control" type="email" name="email" required placeholder="example@email.com" />
                            <small class="form-text text-muted">البريد الإلكتروني لتسجيل الدخول والتواصل</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-phone me-1"></i>رقم الهاتف
                            </label>
                            <input class="form-control" name="phone" placeholder="رقم الهاتف" />
                            <small class="form-text text-muted">رقم الهاتف للتواصل المباشر</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>المحافظة
                            </label>
                            <select class="form-control" name="governorate_id" id="governorate_id">
                                <option value="">اختر المحافظة</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}">{{ $governorate->name_ar }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">اختر المحافظة من القائمة</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-city me-1"></i>المدينة
                            </label>
                            <select class="form-control" name="city_id" id="city_id">
                                <option value="">اختر المدينة</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" data-governorate="{{ $city->governorate_id }}">{{ $city->name_ar }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">اختر المدينة بعد تحديد المحافظة</small>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-pin me-1"></i>العنوان التفصيلي
                            </label>
                            <input class="form-control" name="address" placeholder="العنوان الكامل والتفصيلي" />
                            <small class="form-text text-muted">العنوان الكامل للمورد</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-mail-bulk me-1"></i>الرمز البريدي
                            </label>
                            <input class="form-control" name="postal_code" placeholder="الرمز البريدي" />
                            <small class="form-text text-muted">الرمز البريدي للمنطقة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Business Information -->
        <div class="form-section" id="section-3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="section-title">
                        <h4>معلومات العمل</h4>
                        <p class="section-description">أدخل معلومات النشاط التجاري والتراخيص</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-building me-1"></i>اسم المتجر/الشركة
                            </label>
                            <input class="form-control" name="business_name" placeholder="اسم المتجر أو الشركة" />
                            <small class="form-text text-muted">الاسم التجاري المسجل</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-briefcase me-1"></i>نوع النشاط
                            </label>
                            <select class="form-control" name="business_type">
                                <option value="">اختر نوع النشاط</option>
                                <option value="agriculture">زراعة</option>
                                <option value="livestock">تربية حيوانات</option>
                                <option value="dairy">منتجات ألبان</option>
                                <option value="poultry">دواجن</option>
                                <option value="fishing">صيد</option>
                                <option value="other">أخرى</option>
                            </select>
                            <small class="form-text text-muted">نوع النشاط التجاري الرئيسي</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-file-alt me-1"></i>رقم السجل التجاري
                            </label>
                            <input class="form-control" name="commercial_record" placeholder="رقم السجل التجاري" />
                            <small class="form-text text-muted">رقم السجل التجاري الرسمي</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-receipt me-1"></i>الرقم الضريبي
                            </label>
                            <input class="form-control" name="tax_number" placeholder="الرقم الضريبي" />
                            <small class="form-text text-muted">الرقم الضريبي للمنشأة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Financial Information -->
        <div class="form-section" id="section-4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="section-title">
                        <h4>المعلومات المالية</h4>
                        <p class="section-description">أدخل المعلومات المصرفية ومعلومات التوصيل</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-university me-1"></i>رقم الحساب البنكي
                            </label>
                            <input class="form-control" name="bank_account" placeholder="رقم الحساب البنكي" />
                            <small class="form-text text-muted">رقم الحساب لتحويل الأرباح</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-landmark me-1"></i>اسم البنك
                            </label>
                            <input class="form-control" name="bank_name" placeholder="اسم البنك" />
                            <small class="form-text text-muted">اسم البنك المسجل به الحساب</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-truck me-1"></i>رسوم التوصيل (دينار)
                            </label>
                            <input class="form-control" type="number" step="0.1" min="0" name="delivery_fee" placeholder="0.0" />
                            <small class="form-text text-muted">رسوم التوصيل للطلبات</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-gift me-1"></i>حد التوصيل المجاني (دينار)
                            </label>
                            <input class="form-control" type="number" step="0.1" min="0" name="free_delivery_threshold" placeholder="0.0" />
                            <small class="form-text text-muted">الحد الأدنى للتوصيل المجاني</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-star me-1"></i>التقييم الأولي
                            </label>
                            <input class="form-control" type="number" step="0.1" min="0" max="5" name="rating" placeholder="0.0" />
                            <small class="form-text text-muted">التقييم الأولي للمورد (0-5)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Settings -->
        <div class="form-section" id="section-5">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="section-title">
                        <h4>الإعدادات والحالة</h4>
                        <p class="section-description">حدد حالة المورد والإعدادات الخاصة</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i>حالة الموافقة
                            </label>
                            <select class="form-select" name="status">
                                <option value="approved">مقبول</option>
                                <option value="pending">معلق</option>
                                <option value="rejected">مرفوض</option>
                            </select>
                            <small class="form-text text-muted">حالة موافقة الإدارة على المورد</small>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check-card">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" />
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <div>
                                            <strong>مورد نشط</strong>
                                            <small class="d-block text-muted">يمكن للمورد تسجيل الدخول وإدارة منتجاته</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check-card">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" />
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star me-2"></i>
                                        <div>
                                            <strong>مورد مميز</strong>
                                            <small class="d-block text-muted">يظهر في قائمة الموردين المميزين</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check-card">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_fresh" value="1" id="is_fresh" />
                                    <label class="form-check-label" for="is_fresh">
                                        <i class="fas fa-leaf me-2"></i>
                                        <div>
                                            <strong>منتجات طازجة</strong>
                                            <small class="d-block text-muted">يقدم منتجات طازجة ومحلية</small>
                                        </div>
                                    </label>
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
                    <i class="fas fa-save me-2"></i>حفظ المورد
                </button>
            </div>
        </div>
    </form>
</div>
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

/* Enhanced Checkboxes */
.form-check-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-check-card:hover {
    border-color: #3498db;
    background: rgba(52, 152, 219, 0.05);
    transform: translateY(-2px);
}

.form-check-card .form-check {
    margin: 0;
}

.form-check-card .form-check-input {
    margin-top: 0;
    margin-left: 0;
}

.form-check-card .form-check-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 0;
}

.form-check-card .form-check-input:checked + .form-check-label {
    color: #3498db;
}

.form-check-card .form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
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
    // Governorate and City functionality
    const governorateSelect = document.getElementById('governorate_id');
    const citySelect = document.getElementById('city_id');
    
    governorateSelect.addEventListener('change', function() {
        const selectedGovernorateId = this.value;
        const cityOptions = citySelect.querySelectorAll('option[data-governorate]');
        
        // Reset city select
        citySelect.innerHTML = '<option value="">اختر المدينة</option>';
        
        if (selectedGovernorateId) {
            cityOptions.forEach(option => {
                if (option.dataset.governorate === selectedGovernorateId) {
                    citySelect.appendChild(option.cloneNode(true));
                }
            });
        }
    });

    // Initialize form
    updateStepDisplay();
});

function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep < 1 || newStep > totalSteps) {
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
        // Show error message
        showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
    }
    
    return isValid;
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function showFormGuide() {
    const guideModal = `
        <div class="modal fade" id="formGuideModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-question-circle me-2"></i>دليل إضافة مورد جديد
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="guide-section">
                            <h6><i class="fas fa-user-circle me-2 text-primary"></i>المعلومات الأساسية</h6>
                            <ul>
                                <li>أدخل اسم المورد باللغة الإنجليزية (مطلوب)</li>
                                <li>أضف اسم المورد باللغة العربية للعرض</li>
                                <li>حدد تصنيف المورد (زراعي، ألبان، إلخ)</li>
                                <li>أنشئ كلمة مرور قوية للحساب</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-address-book me-2 text-info"></i>معلومات الاتصال</h6>
                            <ul>
                                <li>أدخل بريد إلكتروني صحيح للتواصل</li>
                                <li>أضف رقم هاتف للتواصل المباشر</li>
                                <li>حدد المحافظة والمدينة من القوائم</li>
                                <li>أدخل العنوان التفصيلي</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-briefcase me-2 text-warning"></i>معلومات العمل</h6>
                            <ul>
                                <li>أدخل اسم المتجر أو الشركة الرسمي</li>
                                <li>حدد نوع النشاط التجاري</li>
                                <li>أضف أرقام التراخيص إن وجدت</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-university me-2 text-success"></i>المعلومات المالية</h6>
                            <ul>
                                <li>أدخل معلومات الحساب البنكي</li>
                                <li>حدد رسوم التوصيل</li>
                                <li>اختر حد التوصيل المجاني</li>
                            </ul>
                        </div>
                        <div class="guide-section">
                            <h6><i class="fas fa-cogs me-2 text-secondary"></i>الإعدادات</h6>
                            <ul>
                                <li>حدد حالة الموافقة على المورد</li>
                                <li>فعّل الحساب للسماح بالدخول</li>
                                <li>اختر الإعدادات الخاصة (مميز، طازج)</li>
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
    const existingModal = document.getElementById('formGuideModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', guideModal);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('formGuideModal'));
    modal.show();
}

// Add CSS for guide modal
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