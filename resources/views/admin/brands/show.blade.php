@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-star me-2"></i>تفاصيل الماركة: {{ $brand->name }}</h3>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($brand->logo)
                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" 
                                         style="width: 200px; height: 200px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 200px; height: 200px; border-radius: 15px; margin: 0 auto;">
                                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">اسم الماركة:</label>
                                        <p class="form-control-plaintext">{{ $brand->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">البلد:</label>
                                        <p class="form-control-plaintext">{{ $brand->country ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الموقع الإلكتروني:</label>
                                        <p class="form-control-plaintext">
                                            @if($brand->website)
                                                <a href="{{ $brand->website }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    {{ $brand->website }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ترتيب العرض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge badge-secondary">{{ $brand->sort_order }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الحالة:</label>
                                        <p class="form-control-plaintext">
                                            @if($brand->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                        <p class="form-control-plaintext">{{ $brand->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الوصف:</label>
                                        <p class="form-control-plaintext">
                                            {{ $brand->description ?? 'لا يوجد وصف' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($brand->products->count() > 0)
                        <hr>
                        <div class="mt-4">
                            <h5><i class="fas fa-box me-2"></i>المنتجات المرتبطة بهذه الماركة ({{ $brand->products->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>الصورة</th>
                                            <th>اسم المنتج</th>
                                            <th>السعر</th>
                                            <th>المخزون</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($brand->products->take(10) as $product)
                                            <tr>
                                                <td>
                                                    @if($product->getMainImage())
                                                        <img src="{{ $product->getMainImage() }}" alt="{{ $product->name }}" 
                                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; border-radius: 4px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ number_format($product->price, 3) }} د.ك</td>
                                                <td>{{ $product->stock }}</td>
                                                <td>
                                                    @if($product->is_active)
                                                        <span class="badge badge-success">نشط</span>
                                                    @else
                                                        <span class="badge badge-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($brand->products->count() > 10)
                                <p class="text-muted">عرض 10 من أصل {{ $brand->products->count() }} منتج</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
