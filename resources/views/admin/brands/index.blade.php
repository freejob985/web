@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-star me-2"></i>إدارة الماركات</h3>
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>إضافة ماركة جديدة
                    </a>
                </div>
                <div class="card-body">
                    @if($brands->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>الشعار</th>
                                        <th>اسم الماركة</th>
                                        <th>الوصف</th>
                                        <th>الموقع الإلكتروني</th>
                                        <th>البلد</th>
                                        <th>الحالة</th>
                                        <th>ترتيب العرض</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as $brand)
                                        <tr>
                                            <td>
                                                @if($brand->logo)
                                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; border-radius: 8px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $brand->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ Str::limit($brand->description, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($brand->website)
                                                    <a href="{{ $brand->website }}" target="_blank" class="text-primary">
                                                        <i class="fas fa-external-link-alt me-1"></i>
                                                        {{ Str::limit($brand->website, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $brand->country ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($brand->is_active)
                                                    <span class="badge badge-success">نشط</span>
                                                @else
                                                    <span class="badge badge-danger">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $brand->sort_order }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $brand->created_at->format('Y-m-d') }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.brands.show', $brand) }}" 
                                                       class="btn btn-sm btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.brands.edit', $brand) }}" 
                                                       class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteBrand({{ $brand->id }})" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $brands->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-star"></i>
                            <h4>لا توجد ماركات</h4>
                            <p>لم يتم إضافة أي ماركات بعد. ابدأ بإضافة ماركة جديدة.</p>
                            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>إضافة ماركة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذه الماركة؟</p>
                <p class="text-danger"><strong>تحذير:</strong> لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBrand(brandId) {
    document.getElementById('deleteForm').action = `/admin/brands/${brandId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
