@extends('admin.layouts.app')

@section('title', 'سجل الأخطاء')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        سجل الأخطاء
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.errors.download') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-download"></i> تحميل الملف
                        </a>
                        <form method="POST" action="{{ route('admin.errors.clear') }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من مسح جميع الأخطاء؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> مسح الأخطاء
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="date" class="form-label">التاريخ</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="level" class="form-label">مستوى الخطأ</label>
                            <select class="form-select" id="level" name="level">
                                <option value="">جميع المستويات</option>
                                <option value="ERROR" {{ request('level') == 'ERROR' ? 'selected' : '' }}>خطأ</option>
                                <option value="WARNING" {{ request('level') == 'WARNING' ? 'selected' : '' }}>تحذير</option>
                                <option value="INFO" {{ request('level') == 'INFO' ? 'selected' : '' }}>معلومات</option>
                                <option value="DEBUG" {{ request('level') == 'DEBUG' ? 'selected' : '' }}>تصحيح</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">البحث في الرسالة</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="ابحث في رسائل الأخطاء...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                            <p class="mb-0">إجمالي الأخطاء</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['warnings'] }}</h4>
                                            <p class="mb-0">تحذيرات</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['info'] }}</h4>
                                            <p class="mb-0">معلومات</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-info-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $stats['debug'] }}</h4>
                                            <p class="mb-0">تصحيح</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-bug fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Errors List -->
                    @if($paginatedErrors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>التاريخ والوقت</th>
                                        <th>المستوى</th>
                                        <th>الرسالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paginatedErrors as $index => $error)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ $error['date'] }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($error['date'])->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $levelColors = [
                                                        'ERROR' => 'danger',
                                                        'WARNING' => 'warning',
                                                        'INFO' => 'info',
                                                        'DEBUG' => 'secondary'
                                                    ];
                                                    $color = $levelColors[$error['level']] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ $error['level'] }}</span>
                                            </td>
                                            <td>
                                                <div class="error-message">
                                                    <p class="mb-1 text-truncate" style="max-width: 400px;" title="{{ $error['message'] }}">
                                                        {{ $error['message'] }}
                                                    </p>
                                                    @if(count($error['context']) > 0)
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i>
                                                            {{ count($error['context']) }} سياق إضافي
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.errors.show', $index) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> عرض
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyError({{ $index }})">
                                                        <i class="fas fa-copy"></i> نسخ
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($totalPages > 1)
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    @if($currentPage > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?page={{ $currentPage - 1 }}{{ request('date') ? '&date=' . request('date') : '' }}{{ request('level') ? '&level=' . request('level') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}">السابق</a>
                                        </li>
                                    @endif
                                    
                                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link" href="?page={{ $i }}{{ request('date') ? '&date=' . request('date') : '' }}{{ request('level') ? '&level=' . request('level') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    
                                    @if($currentPage < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link" href="?page={{ $currentPage + 1 }}{{ request('date') ? '&date=' . request('date') : '' }}{{ request('level') ? '&level=' . request('level') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}">التالي</a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h4 class="text-muted">لا توجد أخطاء</h4>
                            <p class="text-muted">النظام يعمل بشكل طبيعي ولا توجد أخطاء مسجلة.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Copy Error Modal -->
<div class="modal fade" id="copyErrorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نسخ تفاصيل الخطأ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="errorDetails" class="form-control" rows="15" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="copyToClipboard()">نسخ إلى الحافظة</button>
            </div>
        </div>
    </div>
</div>

<script>
const errors = @json($paginatedErrors);

function copyError(index) {
    const error = errors[index];
    if (!error) return;
    
    let errorText = `التاريخ: ${error.date}\n`;
    errorText += `المستوى: ${error.level}\n`;
    errorText += `الرسالة: ${error.message}\n\n`;
    
    if (error.context && error.context.length > 0) {
        errorText += `السياق:\n`;
        error.context.forEach(ctx => {
            errorText += `- ${ctx}\n`;
        });
        errorText += `\n`;
    }
    
    if (error.stack_trace) {
        errorText += `Stack Trace:\n${error.stack_trace}`;
    }
    
    document.getElementById('errorDetails').value = errorText;
    new bootstrap.Modal(document.getElementById('copyErrorModal')).show();
}

function copyToClipboard() {
    const textarea = document.getElementById('errorDetails');
    textarea.select();
    document.execCommand('copy');
    
    // Show success message
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'تم النسخ!';
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-success');
    
    setTimeout(() => {
        btn.textContent = originalText;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-primary');
    }, 2000);
}
</script>
@endsection
