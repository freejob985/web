@extends('admin.layouts.app')

@section('title', 'تفاصيل المدينة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل المدينة: {{ $city->name_ar }}</h3>
                    <div>
                        <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
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
                                    <td>{{ $city->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (إنجليزي)</th>
                                    <td>{{ $city->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>الكود</th>
                                    <td><code>{{ $city->code }}</code></td>
                                </tr>
                                <tr>
                                    <th>المحافظة</th>
                                    <td>{{ $city->governorate->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة</th>
                                    <td>
                                        @if($city->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>ترتيب العرض</th>
                                    <td>{{ $city->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء</th>
                                    <td>{{ $city->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ التحديث</th>
                                    <td>{{ $city->updated_at->format('Y-m-d H:i') }}</td>
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
                                                <h4 class="text-success">{{ $city->products_count }}</h4>
                                                <p class="mb-0">المنتجات</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-3">
                                                <h4 class="text-warning">{{ $city->vendors_count }}</h4>
                                                <p class="mb-0">الموردون</p>
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
    </div>
</div>
@endsection
