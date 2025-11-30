@extends('admin.layouts.app')

@section('title', 'تفاصيل القسم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل القسم: {{ $category->name_ar }}</h3>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name_ar }}" class="img-fluid rounded">
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x"></i>
                                    <p>لا توجد صورة</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">الاسم (عربي):</th>
                                    <td>{{ $category->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (إنجليزي):</th>
                                    <td>{{ $category->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>الرابط:</th>
                                    <td><code>{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th>الوصف (عربي):</th>
                                    <td>{{ $category->description_ar ?: 'لا يوجد وصف' }}</td>
                                </tr>
                                <tr>
                                    <th>الوصف (إنجليزي):</th>
                                    <td>{{ $category->description_en ?: 'لا يوجد وصف' }}</td>
                                </tr>
                                <tr>
                                    <th>الأيقونة:</th>
                                    <td>
                                        @if($category->icon)
                                            <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                                        @else
                                            لا توجد أيقونة
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض:</th>
                                    <td>{{ $category->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>الأقسام الفرعية ({{ $category->subcategories->count() }})</h5>
                            @if($category->subcategories->count() > 0)
                                <div class="list-group">
                                    @foreach($category->subcategories as $subcategory)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $subcategory->name_ar }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $subcategory->name_en }}</small>
                                        </div>
                                        <div>
                                            @if($subcategory->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">لا توجد أقسام فرعية</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>المنتجات ({{ $category->products->count() }})</h5>
                            @if($category->products->count() > 0)
                                <div class="list-group">
                                    @foreach($category->products->take(5) as $product)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $product->formatted_price }}</small>
                                        </div>
                                        <div>
                                            @if($product->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($category->products->count() > 5)
                                        <div class="list-group-item text-center">
                                            <small class="text-muted">و {{ $category->products->count() - 5 }} منتج آخر</small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-muted">لا توجد منتجات</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
