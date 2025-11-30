@extends('admin.layouts.app')

@section('title', 'تعديل طريقة التواصل')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل طريقة التواصل: {{ $contactMethod->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.contact-methods.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.contact-methods.update', $contactMethod->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">الاسم</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $contactMethod->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">الأيقونة (Font Awesome)</label>
                            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $contactMethod->icon) }}" placeholder="مثال: fas fa-phone">
                            <small class="form-text text-muted">يمكنك استخدام أيقونات Font Awesome مثل: fas fa-phone, fab fa-whatsapp</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="value">القيمة</label>
                            <input type="text" name="value" id="value" class="form-control" value="{{ old('value', $contactMethod->value) }}" required>
                            <small class="form-text text-muted">مثال: رقم الهاتف، البريد الإلكتروني، إلخ</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="link">الرابط (اختياري)</label>
                            <input type="text" name="link" id="link" class="form-control" value="{{ old('link', $contactMethod->link) }}">
                            <small class="form-text text-muted">مثال: tel:+123456789, mailto:example@example.com, https://wa.me/123456789</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">الترتيب</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $contactMethod->sort_order) }}">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $contactMethod->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">مفعل</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection