@extends('admin.layouts.app')

@section('title', 'تعديل الإعداد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل الإعداد: {{ $setting->label }}</h3>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.settings.update-setting', $setting) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="key">مفتاح الإعداد</label>
                                    <input type="text" class="form-control" value="{{ $setting->key }}" readonly>
                                    <small class="form-text text-muted">لا يمكن تغيير مفتاح الإعداد</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group">المجموعة <span class="text-danger">*</span></label>
                                    <select class="form-control @error('group') is-invalid @enderror" 
                                            id="group" name="group" required>
                                        @foreach($groups as $groupKey => $groupName)
                                            <option value="{{ $groupKey }}" {{ old('group', $setting->group) == $groupKey ? 'selected' : '' }}>
                                                {{ $groupName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="label">التسمية <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('label') is-invalid @enderror" 
                                           id="label" name="label" value="{{ old('label', $setting->label) }}" required>
                                    @error('label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">النوع <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        @foreach($types as $typeKey => $typeName)
                                            <option value="{{ $typeKey }}" {{ old('type', $setting->type) == $typeKey ? 'selected' : '' }}>
                                                {{ $typeName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="value">القيمة</label>
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" name="value" rows="3">{{ old('value', $setting->value) }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $setting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" 
                                       value="1" {{ old('is_public', $setting->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    متاح عبر API
                                </label>
                            </div>
                            <small class="form-text text-muted">إذا تم تفعيله، يمكن الوصول إليه عبر API</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">تحديث الإعداد</button>
                            <a href="{{ route('admin.settings.show', $setting->group) }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
