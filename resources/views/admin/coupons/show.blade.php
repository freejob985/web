@extends('admin.layouts.app')

@section('title', 'تفاصيل الكوبون')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل الكوبون: {{ $coupon->name }}</h3>
                    <div>
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">المعلومات الأساسية</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>كود الكوبون:</strong></td>
                                            <td><code class="bg-light p-2 rounded">{{ $coupon->code }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>اسم الكوبون:</strong></td>
                                            <td>{{ $coupon->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الوصف:</strong></td>
                                            <td>{{ $coupon->description ?: 'لا يوجد وصف' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحالة:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $coupon->status == 'active' ? 'success' : ($coupon->status == 'expired' ? 'danger' : 'secondary') }}">
                                                    {{ $coupon->status == 'active' ? 'نشط' : ($coupon->status == 'expired' ? 'منتهي' : 'غير نشط') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الإنشاء:</strong></td>
                                            <td>{{ $coupon->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>آخر تحديث:</strong></td>
                                            <td>{{ $coupon->updated_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معلومات الخصم</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>نوع الخصم:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $coupon->discount_type == 'fixed' ? 'info' : 'success' }}">
                                                    {{ $coupon->discount_type == 'fixed' ? 'مبلغ ثابت' : 'نسبة مئوية' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>قيمة الخصم:</strong></td>
                                            <td>
                                                @if($coupon->discount_type == 'fixed')
                                                    {{ number_format($coupon->discount_value, 3) }} دينار
                                                @else
                                                    {{ $coupon->discount_value }}%
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحد الأدنى للطلب:</strong></td>
                                            <td>
                                                @if($coupon->minimum_amount)
                                                    {{ number_format($coupon->minimum_amount, 3) }} دينار
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحد الأقصى للخصم:</strong></td>
                                            <td>
                                                @if($coupon->maximum_discount)
                                                    {{ number_format($coupon->maximum_discount, 3) }} دينار
                                                @else
                                                    <span class="text-muted">لا يوجد</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Usage Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معلومات الاستخدام</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>نوع حد الاستخدام:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $coupon->usage_limit_type == 'unlimited' ? 'success' : 'warning' }}">
                                                    {{ $coupon->usage_limit_type == 'unlimited' ? 'غير محدود' : 'محدود' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>حد الاستخدام:</strong></td>
                                            <td>
                                                @if($coupon->usage_limit_type == 'limited')
                                                    {{ $coupon->usage_limit }}
                                                @else
                                                    <span class="text-muted">∞</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>عدد مرات الاستخدام:</strong></td>
                                            <td>
                                                <span class="badge badge-primary">{{ $coupon->used_count }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الاستخدامات المتبقية:</strong></td>
                                            <td>
                                                @if($coupon->usage_limit_type == 'unlimited')
                                                    <span class="text-muted">∞</span>
                                                @else
                                                    <span class="badge badge-{{ ($coupon->usage_limit - $coupon->used_count) > 0 ? 'success' : 'danger' }}">
                                                        {{ max(0, $coupon->usage_limit - $coupon->used_count) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Time Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معلومات الوقت</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>تاريخ البداية:</strong></td>
                                            <td>
                                                @if($coupon->starts_at)
                                                    {{ $coupon->starts_at->format('Y-m-d H:i') }}
                                                @else
                                                    <span class="text-muted">فوري</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الانتهاء:</strong></td>
                                            <td>
                                                @if($coupon->expires_at)
                                                    {{ $coupon->expires_at->format('Y-m-d H:i') }}
                                                    @if($coupon->expires_at->isPast())
                                                        <span class="badge badge-danger ml-2">منتهي</span>
                                                    @elseif($coupon->expires_at->isFuture())
                                                        <span class="badge badge-success ml-2">نشط</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">لا ينتهي</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>صالح للاستخدام:</strong></td>
                                            <td>
                                                @if($coupon->is_valid)
                                                    <span class="badge badge-success">نعم</span>
                                                @else
                                                    <span class="badge badge-danger">لا</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Applicable Products -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">المنتجات المطبقة عليها</h5>
                                </div>
                                <div class="card-body">
                                    @if($coupon->applicable_type == 'all_products')
                                        <span class="badge badge-success">جميع المنتجات</span>
                                    @elseif($coupon->applicable_type == 'specific_products')
                                        <h6>المنتجات المحددة:</h6>
                                        @if($coupon->applicable_products && count($coupon->applicable_products) > 0)
                                            <div class="row">
                                                @foreach($coupon->applicable_products as $productId)
                                                    @php
                                                        $product = \App\Models\Product::find($productId);
                                                    @endphp
                                                    @if($product)
                                                        <div class="col-md-3 mb-2">
                                                            <span class="badge badge-info">{{ $product->name }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">لا توجد منتجات محددة</span>
                                        @endif
                                    @elseif($coupon->applicable_type == 'specific_categories')
                                        <h6>الأقسام المحددة:</h6>
                                        @if($coupon->applicable_categories && count($coupon->applicable_categories) > 0)
                                            <div class="row">
                                                @foreach($coupon->applicable_categories as $categoryId)
                                                    @php
                                                        $category = \App\Models\Category::find($categoryId);
                                                    @endphp
                                                    @if($category)
                                                        <div class="col-md-3 mb-2">
                                                            <span class="badge badge-info">{{ $category->name }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">لا توجد أقسام محددة</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
