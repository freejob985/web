@extends('admin.layouts.app')

@section('title', 'تفاصيل العرض')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        تفاصيل العرض: {{ $offer->title }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.offers.edit', $offer) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            تعديل
                        </a>
                        <a href="{{ route('admin.offers.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-1"></i>
                            العودة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($offer->image)
                                    <img src="{{ asset('storage/' . $offer->image) }}" 
                                         alt="{{ $offer->title }}" 
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
                                        <label class="form-label fw-bold">عنوان العرض:</label>
                                        <p class="form-control-plaintext">{{ $offer->title }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">قسم العرض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $offer->category->color ?? 'bg-secondary' }} px-3 py-2">
                                                {{ $offer->category->name ?? 'غير محدد' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">وصف العرض:</label>
                                <p class="form-control-plaintext">
                                    {{ $offer->description ?: 'لا يوجد وصف' }}
                                </p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">السعر الأصلي:</label>
                                        <p class="form-control-plaintext">
                                            <span class="h5 text-muted">{{ number_format($offer->original_price, 2) }} د.ك</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">سعر العرض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="h5 text-primary">{{ number_format($offer->offer_price, 2) }} د.ك</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">نسبة الخصم:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-danger fs-6">{{ $offer->discount_percentage }}%</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">نوع الخصم:</label>
                                        <p class="form-control-plaintext">
                                            @switch($offer->discount_type)
                                                @case('percentage')
                                                    <span class="badge bg-info">نسبة مئوية</span>
                                                    @break
                                                @case('fixed')
                                                    <span class="badge bg-info">مبلغ ثابت</span>
                                                    @break
                                                @case('buy_x_get_y')
                                                    <span class="badge bg-info">اشتر X واحصل على Y</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">غير محدد</span>
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الحالة:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $offer->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                <i class="fas {{ $offer->is_active ? 'fa-check' : 'fa-times' }} me-1"></i>
                                                {{ $offer->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ترتيب العرض:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info fs-6">{{ $offer->sort_order }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($offer->discount_type === 'buy_x_get_y')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">اشتر (X):</label>
                                            <p class="form-control-plaintext">{{ $offer->buy_quantity }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">احصل على (Y):</label>
                                            <p class="form-control-plaintext">{{ $offer->get_quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ البداية:</label>
                                        <p class="form-control-plaintext">
                                            {{ $offer->start_date ? $offer->start_date->format('Y-m-d H:i:s') : 'غير محدد' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ النهاية:</label>
                                        <p class="form-control-plaintext">
                                            {{ $offer->end_date ? $offer->end_date->format('Y-m-d H:i:s') : 'غير محدد' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">مميز:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $offer->is_featured ? 'bg-warning' : 'bg-secondary' }} fs-6">
                                                <i class="fas fa-star me-1"></i>
                                                {{ $offer->is_featured ? 'نعم' : 'لا' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">بيع سريع:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $offer->is_limited_time ? 'bg-danger' : 'bg-secondary' }} fs-6">
                                                <i class="fas fa-bolt me-1"></i>
                                                {{ $offer->is_limited_time ? 'نعم' : 'لا' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">المبلغ المحفوظ:</label>
                                        <p class="form-control-plaintext">
                                            <span class="h6 text-success">
                                                {{ number_format($offer->original_price - $offer->offer_price, 2) }} د.ك
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                                        <p class="form-control-plaintext">{{ $offer->created_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">آخر تحديث:</label>
                                        <p class="form-control-plaintext">{{ $offer->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($offer->end_date && $offer->end_date->isFuture())
                        <hr>
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>
                            <strong>ينتهي العرض خلال:</strong>
                            <span id="countdown"></span>
                        </div>
                        
                        <script>
                        // Countdown timer
                        function updateCountdown() {
                            const endDate = new Date('{{ $offer->end_date->toISOString() }}');
                            const now = new Date();
                            const diff = endDate - now;
                            
                            if (diff > 0) {
                                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                
                                document.getElementById('countdown').innerHTML = 
                                    `${days} يوم ${hours} ساعة ${minutes} دقيقة ${seconds} ثانية`;
                            } else {
                                document.getElementById('countdown').innerHTML = 'انتهى العرض';
                            }
                        }
                        
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
