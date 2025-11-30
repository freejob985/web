@extends('admin.layouts.app')

@section('title', 'تفاصيل القسم الفرعي')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل القسم الفرعي: {{ $subcategory->name_ar }}</h3>
                    <div>
                        <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">الاسم (عربي)</th>
                                    <td>{{ $subcategory->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (إنجليزي)</th>
                                    <td>{{ $subcategory->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>القسم الرئيسي</th>
                                    <td>{{ $subcategory->category->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الرابط</th>
                                    <td><code>{{ $subcategory->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th>الحالة</th>
                                    <td>
                                        @if($subcategory->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض</th>
                                    <td>{{ $subcategory->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء</th>
                                    <td>{{ $subcategory->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التحديث</th>
                                    <td>{{ $subcategory->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>الصورة</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($subcategory->image)
                                        <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name_ar }}" 
                                             class="img-fluid" style="max-width: 200px;">
                                    @else
                                        <p class="text-muted">لا توجد صورة</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>الإحصائيات</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-12">
                                            <div class="border rounded p-3">
                                                <h4 class="text-success">{{ $subcategory->products_count }}</h4>
                                                <p class="mb-0">المنتجات</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($subcategory->description_ar || $subcategory->description_en)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>الوصف</h5>
                                </div>
                                <div class="card-body">
                                    @if($subcategory->description_ar)
                                        <h6>الوصف (عربي)</h6>
                                        <p>{{ $subcategory->description_ar }}</p>
                                    @endif
                                    
                                    @if($subcategory->description_en)
                                        <h6>الوصف (إنجليزي)</h6>
                                        <p>{{ $subcategory->description_en }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
