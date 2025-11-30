@extends('admin.layouts.app')

@section('title', $groupLabels[$group])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $groupLabels[$group] }}</h3>
                    <div>
                        <button class="btn btn-warning" onclick="resetSettings()">
                            <i class="fas fa-undo"></i> إعادة تعيين
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.settings.update', $group) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            @foreach($settings as $setting)
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">{{ $setting->label }}</label>
                                    
                                    @if($setting->type == 'boolean')
                                        <select class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}">
                                            <option value="1" {{ $setting->getValue() ? 'selected' : '' }}>نعم</option>
                                            <option value="0" {{ !$setting->getValue() ? 'selected' : '' }}>لا</option>
                                        </select>
                                    @elseif($setting->type == 'number')
                                        <input type="number" class="form-control" id="{{ $setting->key }}" 
                                               name="{{ $setting->key }}" value="{{ $setting->getValue() }}" 
                                               step="0.001">
                                    @elseif($setting->type == 'text')
                                        <textarea class="form-control" id="{{ $setting->key }}" 
                                                  name="{{ $setting->key }}" rows="3">{{ $setting->getValue() }}</textarea>
                                    @elseif($setting->type == 'json')
                                        <textarea class="form-control" id="{{ $setting->key }}" 
                                                  name="{{ $setting->key }}" rows="3">{{ json_encode($setting->getValue(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                                    @elseif($setting->type == 'file')
                                        <div class="file-upload-container">
                                            <input type="file" class="form-control" id="{{ $setting->key }}" 
                                                   name="{{ $setting->key }}" accept="image/*" onchange="previewImage(this, 'preview-{{ $setting->key }}')">
                                            
                                            <!-- معاينة الصورة الحالية -->
                                            @if($setting->getValue())
                                                <div class="mt-2" id="current-{{ $setting->key }}">
                                                    <img src="{{ asset('storage/' . $setting->getValue()) }}" 
                                                         alt="{{ $setting->label }}" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 200px; max-height: 100px;">
                                                    <div class="mt-1">
                                                        <small class="text-muted">الصورة الحالية: {{ $setting->getValue() }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- معاينة الصورة الجديدة -->
                                            <div class="mt-2" id="preview-{{ $setting->key }}" style="display: none;">
                                                <img id="preview-img-{{ $setting->key }}" 
                                                     alt="معاينة الصورة الجديدة" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 200px; max-height: 100px;">
                                                <div class="mt-1">
                                                    <small class="text-info">معاينة الصورة الجديدة</small>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="text" class="form-control" id="{{ $setting->key }}" 
                                               name="{{ $setting->key }}" value="{{ $setting->getValue() }}">
                                    @endif
                                    
                                    @if($setting->description)
                                        <small class="form-text text-muted">{{ $setting->description }}</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ الإعدادات
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetSettings() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع الإعدادات في هذه المجموعة إلى القيم الافتراضية؟')) {
        fetch('{{ route("admin.settings.reset", $group) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        });
    }
}

// معاينة الصورة عند رفعها
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById('preview-img-' + input.name);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endsection
