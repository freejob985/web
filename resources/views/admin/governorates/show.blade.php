@extends('admin.layouts.app')

@section('title', 'تفاصيل المحافظة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل المحافظة: {{ $governorate->name_ar }}</h3>
                    <div>
                        <a href="{{ route('admin.governorates.edit', $governorate) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.governorates.index') }}" class="btn btn-secondary">
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
                                    <td>{{ $governorate->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (إنجليزي)</th>
                                    <td>{{ $governorate->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>الكود</th>
                                    <td><code>{{ $governorate->code }}</code></td>
                                </tr>
                                <tr>
                                    <th>الحالة</th>
                                    <td>
                                        @if($governorate->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض</th>
                                    <td>{{ $governorate->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء</th>
                                    <td>{{ $governorate->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التحديث</th>
                                    <td>{{ $governorate->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>الإحصائيات</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border rounded p-3">
                                                <h4 class="text-primary">{{ $governorate->cities_count }}</h4>
                                                <p class="mb-0">المدن</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-3">
                                                <h4 class="text-success">{{ $governorate->products_count }}</h4>
                                                <p class="mb-0">المنتجات</p>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <div class="border rounded p-3">
                                                <h4 class="text-warning">{{ $governorate->vendors_count }}</h4>
                                                <p class="mb-0">الموردون</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($governorate->cities->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>المدن التابعة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>الاسم (عربي)</th>
                                                    <th>الاسم (إنجليزي)</th>
                                                    <th>الكود</th>
                                                    <th>الحالة</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($governorate->cities as $city)
                                                <tr>
                                                    <td>{{ $city->name_ar }}</td>
                                                    <td>{{ $city->name_en }}</td>
                                                    <td><code>{{ $city->code }}</code></td>
                                                    <td>
                                                        @if($city->is_active)
                                                            <span class="badge badge-success">نشط</span>
                                                        @else
                                                            <span class="badge badge-danger">غير نشط</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.cities.show', $city) }}" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
