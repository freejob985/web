@extends('admin.layouts.app')

@section('title', 'تعديل قسم العرض')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تعديل قسم العرض: {{ $offerCategory->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.offer-categories.update', $offerCategory) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $offerCategory->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">الرابط (Slug)</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug', $offerCategory->slug) }}">
                                    <div class="form-text">سيتم إنشاؤه تلقائياً إذا ترك فارغاً</div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $offerCategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">صورة القسم</label>
                                    @if($offerCategory->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $offerCategory->image) }}" 
                                                 alt="{{ $offerCategory->name }}" 
                                                 class="img-thumbnail" 
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <div class="form-text">الصورة الحالية</div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">الصور المقبولة: JPG, PNG, GIF (حد أقصى 2MB)</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">لون القسم</label>
                                    <select class="form-select @error('color') is-invalid @enderror" 
                                            id="color" name="color">
                                        <option value="bg-blue-100 text-blue-600" {{ old('color', $offerCategory->color) == 'bg-blue-100 text-blue-600' ? 'selected' : '' }}>أزرق</option>
                                        <option value="bg-green-100 text-green-600" {{ old('color', $offerCategory->color) == 'bg-green-100 text-green-600' ? 'selected' : '' }}>أخضر</option>
                                        <option value="bg-red-100 text-red-600" {{ old('color', $offerCategory->color) == 'bg-red-100 text-red-600' ? 'selected' : '' }}>أحمر</option>
                                        <option value="bg-yellow-100 text-yellow-600" {{ old('color', $offerCategory->color) == 'bg-yellow-100 text-yellow-600' ? 'selected' : '' }}>أصفر</option>
                                        <option value="bg-purple-100 text-purple-600" {{ old('color', $offerCategory->color) == 'bg-purple-100 text-purple-600' ? 'selected' : '' }}>بنفسجي</option>
                                        <option value="bg-pink-100 text-pink-600" {{ old('color', $offerCategory->color) == 'bg-pink-100 text-pink-600' ? 'selected' : '' }}>وردي</option>
                                        <option value="bg-orange-100 text-orange-600" {{ old('color', $offerCategory->color) == 'bg-orange-100 text-orange-600' ? 'selected' : '' }}>برتقالي</option>
                                        <option value="bg-indigo-100 text-indigo-600" {{ old('color', $offerCategory->color) == 'bg-indigo-100 text-indigo-600' ? 'selected' : '' }}>نيلي</option>
                                    </select>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $offerCategory->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $offerCategory->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            نشط
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.offer-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name
        .toLowerCase()
        .replace(/[^a-z0-9\u0600-\u06FF\s]/g, '') // Keep Arabic and English letters, numbers, spaces
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
        .trim();
    
    document.getElementById('slug').value = slug;
});
</script>
@endsection
