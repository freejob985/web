@extends('admin.layouts.app')

@section('title', 'تفاصيل قسم العرض')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        تفاصيل قسم العرض: {{ $offerCategory->name }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.offer-categories.edit', $offerCategory) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            تعديل
                        </a>
                        <a href="{{ route('admin.offer-categories.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-1"></i>
                            العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($offerCategory->image)
                                    <img src="{{ asset('storage/' . $offerCategory->image) }}" 
                                         alt="{{ $offerCategory->name }}" 
                                         class="img-fluid rounded shadow" 
                                         style="max-height: 300px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                         style="height: 300px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">اسم القسم:</label>
                                        <p class="form-control-plaintext">{{ $offerCategory->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الرابط (Slug):</label>
                                        <p class="form-control-plaintext">
                                            <code>{{ $offerCategory->slug }}</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">الوصف:</label>
                                <p class="form-control-plaintext">
                                    {{ $offerCategory->description ?: 'لا يوجد وصف' }}
                                </p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">اللون:</label>
                                        <div>
                                            <span class="badge {{ $offerCategory->color }} px-3 py-2 fs-6">
                                                {{ $offerCategory->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الحالة:</label>
                                        <div>
                                            <span class="badge {{ $offerCategory->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                <i class="fas {{ $offerCategory->is_active ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                {{ $offerCategory->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ترتيب العرض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info fs-6">{{ $offerCategory->sort_order }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">عدد العروض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-primary fs-6">{{ $offerCategory->offers_count ?? 0 }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                        <p class="form-control-plaintext">{{ $offerCategory->created_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">آخر تحديث:</label>
                                        <p class="form-control-plaintext">{{ $offerCategory->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($offerCategory->offers && $offerCategory->offers->count() > 0)
                        <hr>
                        <h5 class="mb-3">
                            <i class="fas fa-tags me-2"></i>
                            العروض في هذا القسم ({{ $offerCategory->offers->count() }})
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>العنوان</th>
                                        <th>السعر الأصلي</th>
                                        <th>سعر العرض</th>
                                        <th>الخصم</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offerCategory->offers->take(10) as $offer)
                                        <tr>
                                            <td>{{ $offer->id }}</td>
                                            <td>
                                                <a href="{{ route('admin.offers.show', $offer) }}" class="text-decoration-none">
                                                    {{ Str::limit($offer->title, 30) }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($offer->original_price, 2) }} د.ك</td>
                                            <td>{{ number_format($offer->new_price, 2) }} د.ك</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $offer->discount_percentage }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $offer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $offer->is_active ? 'نشط' : 'غير نشط' }}
                                                </span>
                                            </td>
                                            <td>{{ $offer->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($offerCategory->offers->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.offers.index', ['category' => $offerCategory->id]) }}" 
                                   class="btn btn-outline-primary">
                                    عرض جميع العروض ({{ $offerCategory->offers->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <hr>
                        <div class="text-center py-4">
                            <i class="fas fa-tags fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">لا توجد عروض في هذا القسم</h6>
                            <a href="{{ route('admin.offers.create', ['category' => $offerCategory->id]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                إضافة عرض جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
