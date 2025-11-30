@extends('admin.layouts.app')

@section('title', 'إضافة سؤال شائع')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة سؤال شائع جديد</h3>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question_ar">السؤال بالعربية *</label>
                                    <input type="text" class="form-control @error('question_ar') is-invalid @enderror" 
                                           id="question_ar" name="question_ar" value="{{ old('question_ar') }}" required>
                                    @error('question_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="answer_ar">الإجابة بالعربية *</label>
                                    <textarea class="form-control @error('answer_ar') is-invalid @enderror" 
                                              id="answer_ar" name="answer_ar" rows="4" required>{{ old('answer_ar') }}</textarea>
                                    @error('answer_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">الفئة *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">اختر الفئة</option>
                                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>عام</option>
                                        <option value="orders" {{ old('category') == 'orders' ? 'selected' : '' }}>الطلبات</option>
                                        <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>الدفع</option>
                                        <option value="delivery" {{ old('category') == 'delivery' ? 'selected' : '' }}>التوصيل</option>
                                        <option value="products" {{ old('category') == 'products' ? 'selected' : '' }}>المنتجات</option>
                                        <option value="support" {{ old('category') == 'support' ? 'selected' : '' }}>الدعم</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            نشط
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ
                            </button>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
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
