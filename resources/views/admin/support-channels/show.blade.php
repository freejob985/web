@extends('admin.layouts.app')

@section('title', 'عرض قناة الدعم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عرض قناة الدعم</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.support-channels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة للقائمة
                        </a>
                        <a href="{{ route('admin.support-channels.edit', $supportChannel) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>العنوان بالعربية:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->title_ar }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>العنوان بالإنجليزية:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->title_en ?: 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>الوصف بالعربية:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->description_ar }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>الوصف بالإنجليزية:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->description_en ?: 'غير محدد' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>معلومات التواصل:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->contact_info }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ساعات العمل:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->availability }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>الأيقونة:</label>
                                        <p class="form-control-plaintext">
                                            @if($supportChannel->icon)
                                                <i class="{{ $supportChannel->icon }} fa-2x" style="color: {{ $supportChannel->color }}"></i>
                                                <br><small class="text-muted">{{ $supportChannel->icon }}</small>
                                            @else
                                                غير محدد
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>اللون:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge" style="background-color: {{ $supportChannel->color }}; color: white;">
                                                {{ $supportChannel->color }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>ترتيب العرض:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->sort_order }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>الحالة:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge {{ $supportChannel->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $supportChannel->is_active ? 'مفعل' : 'غير مفعل' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تاريخ الإنشاء:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->created_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>تاريخ آخر تحديث:</label>
                                        <p class="form-control-plaintext">{{ $supportChannel->updated_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معاينة القناة</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($supportChannel->icon)
                                        <div class="mb-3">
                                            <i class="{{ $supportChannel->icon }} fa-3x" style="color: {{ $supportChannel->color }}"></i>
                                        </div>
                                    @endif
                                    <h5>{{ $supportChannel->title_ar }}</h5>
                                    @if($supportChannel->title_en)
                                        <p class="text-muted">{{ $supportChannel->title_en }}</p>
                                    @endif
                                    <p class="text-muted">{{ $supportChannel->description_ar }}</p>
                                    <div class="mt-3">
                                        <strong>التواصل:</strong><br>
                                        {{ $supportChannel->contact_info }}
                                    </div>
                                    <div class="mt-2">
                                        <strong>ساعات العمل:</strong><br>
                                        {{ $supportChannel->availability }}
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
