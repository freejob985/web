@extends('admin.layouts.app')

@section('title', 'عرض السلايدر')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">عرض السلايدر</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="slider-preview" style="height: 400px; background: linear-gradient(45deg, #e0e0e0, #f0f0f0); border-radius: 8px; position: relative; overflow: hidden; margin-bottom: 20px;">
                                @if($slider->background_image)
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url({{ $slider->background_image }}); background-size: cover; background-position: center;"></div>
                                @elseif($slider->gradient_color)
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, var(--{{ str_replace(['from-', 'to-'], '', $slider->gradient_color) }}));"></div>
                                @endif
                                
                                <div style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); color: white; z-index: 2;">
                                    @if($slider->badge)
                                        <div class="badge badge-light mb-2">{{ $slider->badge }}</div>
                                    @endif
                                    <h2 class="mb-2">{{ $slider->title }}</h2>
                                    @if($slider->subtitle)
                                        <h4 class="mb-2">{{ $slider->subtitle }}</h4>
                                    @endif
                                    @if($slider->description)
                                        <p class="mb-3">{{ $slider->description }}</p>
                                    @endif
                                    @if($slider->button_text)
                                        <button class="btn btn-light">{{ $slider->button_text }}</button>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>تفاصيل السلايدر</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>العنوان الرئيسي:</strong></td>
                                            <td>{{ $slider->title }}</td>
                                        </tr>
                                        @if($slider->subtitle)
                                        <tr>
                                            <td><strong>العنوان الفرعي:</strong></td>
                                            <td>{{ $slider->subtitle }}</td>
                                        </tr>
                                        @endif
                                        @if($slider->description)
                                        <tr>
                                            <td><strong>الوصف:</strong></td>
                                            <td>{{ $slider->description }}</td>
                                        </tr>
                                        @endif
                                        @if($slider->button_text)
                                        <tr>
                                            <td><strong>نص الزر:</strong></td>
                                            <td>{{ $slider->button_text }}</td>
                                        </tr>
                                        @endif
                                        @if($slider->button_url)
                                        <tr>
                                            <td><strong>رابط الزر:</strong></td>
                                            <td><a href="{{ $slider->button_url }}" target="_blank">{{ $slider->button_url }}</a></td>
                                        </tr>
                                        @endif
                                        @if($slider->badge)
                                        <tr>
                                            <td><strong>الشارة:</strong></td>
                                            <td><span class="badge badge-info">{{ $slider->badge }}</span></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>ترتيب العرض:</strong></td>
                                            <td><span class="badge badge-secondary">{{ $slider->sort_order }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحالة:</strong></td>
                                            <td>
                                                <span class="badge {{ $slider->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $slider->is_active ? 'مفعل' : 'معطل' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>معلومات إضافية</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>تاريخ الإنشاء:</strong></td>
                                            <td>{{ $slider->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>آخر تحديث:</strong></td>
                                            <td>{{ $slider->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        @if($slider->gradient_color)
                                        <tr>
                                            <td><strong>لون التدرج:</strong></td>
                                            <td>{{ $slider->gradient_color }}</td>
                                        </tr>
                                        @endif
                                        @if($slider->background_image)
                                        <tr>
                                            <td><strong>رابط الصورة الخلفية:</strong></td>
                                            <td><a href="{{ $slider->background_image }}" target="_blank">عرض الصورة</a></td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            @if($slider->image)
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">صورة السلايدر</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" 
                                             class="img-fluid rounded">
                                    </div>
                                </div>
                            @endif

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title">إجراءات سريعة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> تعديل السلايدر
                                        </a>
                                        
                                        <form action="{{ route('admin.sliders.toggle', $slider) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $slider->is_active ? 'btn-secondary' : 'btn-success' }} w-100">
                                                <i class="fas fa-power-off"></i> 
                                                {{ $slider->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا السلايدر؟')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash"></i> حذف السلايدر
                                            </button>
                                        </form>
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
