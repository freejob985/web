@extends('admin.layouts.app')

@section('title', 'إعدادات النظام')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إعدادات النظام</h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @foreach($groups as $groupKey => $groupName)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($groupKey == 'general')
                                            <i class="fas fa-cog fa-3x text-primary"></i>
                                        @elseif($groupKey == 'email')
                                            <i class="fas fa-envelope fa-3x text-info"></i>
                                        @elseif($groupKey == 'payment')
                                            <i class="fas fa-credit-card fa-3x text-success"></i>
                                        @elseif($groupKey == 'seo')
                                            <i class="fas fa-search fa-3x text-warning"></i>
                                        @elseif($groupKey == 'design')
                                            <i class="fas fa-palette fa-3x text-danger"></i>
                                        @elseif($groupKey == 'developer')
                                            <i class="fas fa-code fa-3x text-secondary"></i>
                                        @endif
                                    </div>
                                    <h5 class="card-title">{{ $groupName }}</h5>
                                    <p class="card-text">
                                        @if($groupKey == 'general')
                                            إعدادات الموقع الأساسية
                                        @elseif($groupKey == 'email')
                                            إعدادات البريد الإلكتروني
                                        @elseif($groupKey == 'payment')
                                            إعدادات الدفع والدفع الإلكتروني
                                        @elseif($groupKey == 'seo')
                                            إعدادات محركات البحث
                                        @elseif($groupKey == 'design')
                                            إعدادات التصميم والألوان
                                        @elseif($groupKey == 'developer')
                                            إعدادات المطور والصيانة
                                        @endif
                                    </p>
                                    <a href="{{ route('admin.settings.show', $groupKey) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> تعديل الإعدادات
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
