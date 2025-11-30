@extends('admin.layouts.app')

@section('title', 'تعديل المحافظة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل المحافظة: {{ $governorate->name_ar }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.governorates.update', $governorate) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">الاسم (عربي) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                           id="name_ar" name="name_ar" value="{{ old('name_ar', $governorate->name_ar) }}" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_en">الاسم (إنجليزي) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                           id="name_en" name="name_en" value="{{ old('name_en', $governorate->name_en) }}" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">الكود <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $governorate->code) }}" required maxlength="10">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $governorate->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $governorate->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            نشط
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.governorates.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
