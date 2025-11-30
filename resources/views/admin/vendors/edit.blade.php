@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit me-2"></i>تعديل المورد: {{ $vendor->name }}</h1>
    <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-store me-2"></i>معلومات المورد</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}">
            @csrf 
            @method('PUT')
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-1"></i>اسم المورد (إنجليزي)
                    </label>
                    <input class="form-control" name="name" value="{{ $vendor->name }}" required placeholder="أدخل اسم المورد بالإنجليزية" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user me-1"></i>اسم المورد (عربي)
                    </label>
                    <input class="form-control" name="name_ar" value="{{ $vendor->name_ar }}" placeholder="أدخل اسم المورد بالعربية" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle me-1"></i>الوصف
                    </label>
                    <textarea class="form-control" name="description" rows="2" placeholder="وصف المورد">{{ $vendor->description }}</textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-tags me-1"></i>التصنيف
                    </label>
                    <input class="form-control" name="category" value="{{ $vendor->category }}" placeholder="تصنيف المورد" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-envelope me-1"></i>البريد الإلكتروني
                    </label>
                    <input class="form-control" type="email" name="email" value="{{ $vendor->email }}" required placeholder="example@email.com" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-lock me-1"></i>كلمة المرور (اتركها فارغة لعدم التغيير)
                    </label>
                    <input class="form-control" type="password" name="password" placeholder="كلمة المرور الجديدة" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-phone me-1"></i>رقم الهاتف
                    </label>
                    <input class="form-control" name="phone" value="{{ $vendor->phone }}" placeholder="رقم الهاتف" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>المحافظة
                    </label>
                    <select class="form-control" name="governorate_id" id="governorate_id">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ $vendor->governorate_id == $governorate->id ? 'selected' : '' }}>
                                {{ $governorate->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-city me-1"></i>المدينة
                    </label>
                    <select class="form-control" name="city_id" id="city_id">
                        <option value="">اختر المدينة</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" data-governorate="{{ $city->governorate_id }}" {{ $vendor->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-city me-1"></i>المدينة (نص حر)
                    </label>
                    <input class="form-control" name="city" value="{{ $vendor->city }}" placeholder="المدينة" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>المحافظة (نص حر)
                    </label>
                    <input class="form-control" name="governorate" value="{{ $vendor->governorate }}" placeholder="المحافظة" />
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-pin me-1"></i>العنوان
                    </label>
                    <input class="form-control" name="address" value="{{ $vendor->address }}" placeholder="العنوان الكامل" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-mail-bulk me-1"></i>الرمز البريدي
                    </label>
                    <input class="form-control" name="postal_code" value="{{ $vendor->postal_code }}" placeholder="الرمز البريدي" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-building me-1"></i>اسم المتجر/الشركة
                    </label>
                    <input class="form-control" name="business_name" value="{{ $vendor->business_name }}" placeholder="اسم المتجر أو الشركة" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-briefcase me-1"></i>نوع النشاط
                    </label>
                    <select class="form-control" name="business_type">
                        <option value="">اختر نوع النشاط</option>
                        <option value="agriculture" {{ $vendor->business_type == 'agriculture' ? 'selected' : '' }}>زراعة</option>
                        <option value="livestock" {{ $vendor->business_type == 'livestock' ? 'selected' : '' }}>تربية حيوانات</option>
                        <option value="dairy" {{ $vendor->business_type == 'dairy' ? 'selected' : '' }}>منتجات ألبان</option>
                        <option value="poultry" {{ $vendor->business_type == 'poultry' ? 'selected' : '' }}>دواجن</option>
                        <option value="fishing" {{ $vendor->business_type == 'fishing' ? 'selected' : '' }}>صيد</option>
                        <option value="other" {{ $vendor->business_type == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-file-alt me-1"></i>رقم السجل التجاري
                    </label>
                    <input class="form-control" name="commercial_record" value="{{ $vendor->commercial_record }}" placeholder="رقم السجل التجاري" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-receipt me-1"></i>الرقم الضريبي
                    </label>
                    <input class="form-control" name="tax_number" value="{{ $vendor->tax_number }}" placeholder="الرقم الضريبي" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-university me-1"></i>رقم الحساب البنكي
                    </label>
                    <input class="form-control" name="bank_account" value="{{ $vendor->bank_account }}" placeholder="رقم الحساب البنكي" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-landmark me-1"></i>اسم البنك
                    </label>
                    <input class="form-control" name="bank_name" value="{{ $vendor->bank_name }}" placeholder="اسم البنك" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-info-circle me-1"></i>الحالة
                    </label>
                    <select class="form-select" name="status">
                        @foreach(['approved'=>'مقبول','pending'=>'معلق','rejected'=>'مرفوض'] as $k=>$v)
                            <option value="{{ $k }}" @selected($vendor->status===$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-toggle-on me-1"></i>الحالة
                    </label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked($vendor->is_active) />
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-check-circle me-1"></i>نشط
                        </label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-star me-1"></i>مميز
                    </label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked($vendor->is_featured) />
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star me-1"></i>مورد مميز
                        </label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="fas fa-leaf me-1"></i>طازج
                    </label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="is_fresh" value="1" id="is_fresh" @checked($vendor->is_fresh) />
                        <label class="form-check-label" for="is_fresh">
                            <i class="fas fa-leaf me-1"></i>منتجات طازجة
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- معلومات التوصيل -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-truck me-2"></i>معلومات التوصيل
                    </h6>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-dollar-sign me-1"></i>رسوم التوصيل (دينار)
                    </label>
                    <input class="form-control" type="number" step="0.1" min="0" name="delivery_fee" value="{{ $vendor->delivery_fee }}" placeholder="0.0" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-gift me-1"></i>حد التوصيل المجاني (دينار)
                    </label>
                    <input class="form-control" type="number" step="0.1" min="0" name="free_delivery_threshold" value="{{ $vendor->free_delivery_threshold }}" placeholder="0.0" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="fas fa-star me-1"></i>التقييم
                    </label>
                    <input class="form-control" type="number" step="0.1" min="0" max="5" name="rating" value="{{ $vendor->rating }}" placeholder="0.0" />
                </div>
            </div>
            
            <div class="d-flex gap-3">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-save me-2"></i>حفظ التعديلات
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.vendors.index') }}">
                    <i class="fas fa-times me-2"></i>إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #3498db;
    font-size: 0.9rem;
    margin-left: 8px;
    width: 16px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
    padding: 10px 15px;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
    display: flex;
    align-items: center;
}

.form-check-label i {
    color: #6c757d;
    margin-left: 8px;
    font-size: 0.8rem;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const governorateSelect = document.getElementById('governorate_id');
    const citySelect = document.getElementById('city_id');
    
    // Function to filter cities based on selected governorate
    function filterCities() {
        const selectedGovernorateId = governorateSelect.value;
        const cityOptions = citySelect.querySelectorAll('option[data-governorate]');
        
        // Hide all city options first
        cityOptions.forEach(option => {
            option.style.display = 'none';
        });
        
        // Show only cities from selected governorate
        if (selectedGovernorateId) {
            cityOptions.forEach(option => {
                if (option.dataset.governorate === selectedGovernorateId) {
                    option.style.display = 'block';
                }
            });
        }
    }
    
    // Initial filter on page load
    filterCities();
    
    // Filter cities when governorate changes
    governorateSelect.addEventListener('change', filterCities);
});
</script>
@endsection