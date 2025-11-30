@extends('admin.layouts.app')

@section('title', 'إحصائيات التقييمات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إحصائيات التقييمات</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reviews.products') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> تقييمات المنتجات
                        </a>
                        <a href="{{ route('admin.reviews.vendors') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-list"></i> تقييمات الموردين
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <!-- Product Reviews Stats -->
                        <div class="col-lg-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">تقييمات المنتجات</h4>
                                            <h2 class="mb-0">{{ $stats['total_product_reviews'] }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-star fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>معتمد:</span>
                                            <span>{{ $stats['approved_product_reviews'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>في الانتظار:</span>
                                            <span>{{ $stats['pending_product_reviews'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>متوسط التقييم:</span>
                                            <span>{{ number_format($stats['average_product_rating'] ?? 0, 2) }}/5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vendor Reviews Stats -->
                        <div class="col-lg-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">تقييمات الموردين</h4>
                                            <h2 class="mb-0">{{ $stats['total_vendor_reviews'] }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-store fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>معتمد:</span>
                                            <span>{{ $stats['approved_vendor_reviews'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>في الانتظار:</span>
                                            <span>{{ $stats['pending_vendor_reviews'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>متوسط التقييم:</span>
                                            <span>{{ number_format($stats['average_vendor_rating'] ?? 0, 2) }}/5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">توزيع تقييمات المنتجات</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="productRatingChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">توزيع تقييمات الموردين</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="vendorRatingChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reviews -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">أحدث التقييمات</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>النوع</th>
                                                    <th>المستخدم</th>
                                                    <th>الهدف</th>
                                                    <th>التقييم</th>
                                                    <th>التعليق</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $recentProductReviews = \App\Models\ProductReview::with(['product', 'user', 'vendor'])
                                                        ->orderBy('created_at', 'desc')
                                                        ->take(5)
                                                        ->get();
                                                    
                                                    $recentVendorReviews = \App\Models\VendorReview::with(['vendor', 'user'])
                                                        ->orderBy('created_at', 'desc')
                                                        ->take(5)
                                                        ->get();
                                                    
                                                    $recentReviews = $recentProductReviews->concat($recentVendorReviews)
                                                        ->sortByDesc('created_at')
                                                        ->take(10);
                                                @endphp
                                                
                                                @forelse($recentReviews as $review)
                                                <tr>
                                                    <td>
                                                        @if($review instanceof \App\Models\ProductReview)
                                                            <span class="badge bg-primary">منتج</span>
                                                        @else
                                                            <span class="badge bg-info">مورد</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $review->display_name }}</td>
                                                    <td>
                                                        @if($review instanceof \App\Models\ProductReview)
                                                            {{ $review->product->name }}
                                                        @else
                                                            {{ $review->vendor->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                            <span class="ms-2">{{ $review->rating }}/5</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $review->comment }}">
                                                            {{ Str::limit($review->comment ?? 'لا يوجد تعليق', 50) }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $review->formatted_date }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">لا توجد تقييمات حديثة</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Rating Chart
    const productCtx = document.getElementById('productRatingChart').getContext('2d');
    new Chart(productCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 نجوم', '4 نجوم', '3 نجوم', '2 نجوم', '1 نجمة'],
            datasets: [{
                data: [
                    {{ \App\Models\ProductReview::where('is_approved', true)->where('rating', 5)->count() }},
                    {{ \App\Models\ProductReview::where('is_approved', true)->where('rating', 4)->count() }},
                    {{ \App\Models\ProductReview::where('is_approved', true)->where('rating', 3)->count() }},
                    {{ \App\Models\ProductReview::where('is_approved', true)->where('rating', 2)->count() }},
                    {{ \App\Models\ProductReview::where('is_approved', true)->where('rating', 1)->count() }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#20c997',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Vendor Rating Chart
    const vendorCtx = document.getElementById('vendorRatingChart').getContext('2d');
    new Chart(vendorCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 نجوم', '4 نجوم', '3 نجوم', '2 نجوم', '1 نجمة'],
            datasets: [{
                data: [
                    {{ \App\Models\VendorReview::where('is_approved', true)->where('rating', 5)->count() }},
                    {{ \App\Models\VendorReview::where('is_approved', true)->where('rating', 4)->count() }},
                    {{ \App\Models\VendorReview::where('is_approved', true)->where('rating', 3)->count() }},
                    {{ \App\Models\VendorReview::where('is_approved', true)->where('rating', 2)->count() }},
                    {{ \App\Models\VendorReview::where('is_approved', true)->where('rating', 1)->count() }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#20c997',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush
