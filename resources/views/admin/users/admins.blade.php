@extends('admin.layouts.app')

@section('title', 'مستخدمو الإدارة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">مستخدمو الإدارة</h1>
                    <p class="text-muted">إدارة حسابات المديرين والمشرفين</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>جميع المستخدمين
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                        <i class="fas fa-plus me-2"></i>إضافة مدير جديد
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>
                            مستخدمو الإدارة ({{ $admins->total() }})
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-primary">نشط</span>
                            <span class="badge bg-light text-secondary">غير نشط</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($admins->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">المعرف</th>
                                        <th class="border-0">الاسم</th>
                                        <th class="border-0">البريد الإلكتروني</th>
                                        <th class="border-0">الهاتف</th>
                                        <th class="border-0">الحالة</th>
                                        <th class="border-0">تاريخ الإنشاء</th>
                                        <th class="border-0">آخر دخول</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-user-shield text-white"></i>
                                                    </div>
                                                    <span class="fw-bold">#{{ $admin->id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong>{{ $admin->name }}</strong>
                                                    <small class="text-muted">{{ $admin->role ?? 'مدير' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-primary">{{ $admin->email }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $admin->phone ?? 'غير محدد' }}</span>
                                            </td>
                                            <td>
                                                @if($admin->is_active ?? true)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check-circle me-1"></i>نشط
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fas fa-times-circle me-1"></i>غير نشط
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $admin->created_at->format('Y-m-d H:i') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    {{ $admin->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @if($admin->last_login_at)
                                                        <i class="fas fa-sign-in-alt me-1"></i>
                                                        {{ $admin->last_login_at->format('Y-m-d H:i') }}
                                                        <small class="d-block">{{ $admin->last_login_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">لم يسجل دخول بعد</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary" 
                                                            title="عرض التفاصيل"
                                                            onclick="viewAdmin({{ $admin->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning" 
                                                            title="تعديل"
                                                            onclick="editAdmin({{ $admin->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @if($admin->id !== auth('admin')->id())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-{{ $admin->is_active ?? true ? 'secondary' : 'success' }}" 
                                                                title="{{ $admin->is_active ?? true ? 'إلغاء التفعيل' : 'تفعيل' }}"
                                                                onclick="toggleAdminStatus({{ $admin->id }})">
                                                            <i class="fas fa-{{ $admin->is_active ?? true ? 'ban' : 'check' }}"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($admins->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        عرض {{ $admins->firstItem() }} إلى {{ $admins->lastItem() }} من {{ $admins->total() }} مدير
                                    </div>
                                    {{ $admins->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا يوجد مديرون</h5>
                            <p class="text-muted">لم يتم إنشاء أي حسابات إدارية بعد</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                <i class="fas fa-plus me-2"></i>إضافة مدير جديد
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مدير جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="addAdminForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف (اختياري)</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                تفعيل الحساب
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>إضافة المدير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewAdmin(adminId) {
    // Implement view admin details
    alert('عرض تفاصيل المدير #' + adminId);
}

function editAdmin(adminId) {
    // Implement edit admin
    alert('تعديل المدير #' + adminId);
}

function toggleAdminStatus(adminId) {
    // Implement toggle admin status
    alert('تغيير حالة المدير #' + adminId);
}
</script>
@endsection
