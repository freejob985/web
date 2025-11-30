@extends('admin.layouts.app')

@section('title', 'تفاصيل الخطأ')

@section('content')
<!-- Highlight.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 mb-2 font-weight-bold">
                                <i class="fas fa-bug me-3"></i>تفاصيل الخطأ
                            </h1>
                            <p class="mb-0 opacity-75">عرض تفصيلي للخطأ مع إمكانية التحليل والنسخ</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.errors.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>العودة للقائمة
                                </a>
                                <button type="button" class="btn btn-success btn-sm" onclick="copyErrorDetails()">
                                    <i class="fas fa-copy me-2"></i>نسخ التفاصيل
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-dark">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                معلومات الخطأ التفصيلية
                            </h5>
                            <p class="mb-0 text-muted small">تحليل شامل للخطأ مع عرض الكود بتنسيق محسن</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="toggleSection('basic-info')">
                                <i class="fas fa-info me-1"></i>المعلومات الأساسية
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="toggleSection('stack-trace')">
                                <i class="fas fa-code me-1"></i>Stack Trace
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Error Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">معلومات أساسية</h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>التاريخ والوقت:</strong></td>
                                            <td>{{ $error['date'] }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>المستوى:</strong></td>
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
                                                <span class="badge bg-{{ $color }} fs-6">{{ $error['level'] }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الوقت المنقضي:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($error['date'])->diffForHumans() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">إحصائيات</h5>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>طول الرسالة:</strong></td>
                                            <td>{{ strlen($error['message']) }} حرف</td>
                                        </tr>
                                        <tr>
                                            <td><strong>عدد عناصر السياق:</strong></td>
                                            <td>{{ count($error['context']) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>طول Stack Trace:</strong></td>
                                            <td>{{ strlen($error['stack_trace']) }} حرف</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Error Message -->
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    رسالة الخطأ
                                </h5>
                                <button class="btn btn-outline-light btn-sm" onclick="copyErrorMessage()">
                                    <i class="fas fa-copy me-1"></i>نسخ
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger border-0 shadow-sm">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <pre class="mb-0 hljs" style="background: transparent; border: none; padding: 0;"><code class="language-text">{{ $error['message'] }}</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Context Information -->
                    @if(count($error['context']) > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle text-info"></i>
                                    معلومات السياق
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>المفتاح</th>
                                                <th>القيمة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($error['context'] as $contextItem)
                                                <tr>
                                                    <td>
                                                        <code>{{ $contextItem }}</code>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">معلومات إضافية</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Stack Trace with Syntax Highlighting -->
                    @if(!empty($error['stack_trace']))
                        <div class="card mb-4" id="stack-trace-section">
                            <div class="card-header bg-dark text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-code me-2"></i>
                                        Stack Trace
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-light btn-sm" onclick="copyStackTrace()">
                                            <i class="fas fa-copy me-1"></i>نسخ
                                        </button>
                                        <button class="btn btn-outline-light btn-sm" onclick="toggleStackTrace()">
                                            <i class="fas fa-expand me-1"></i>توسيع
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <pre class="mb-0 hljs" style="max-height: 500px; overflow-y: auto; overflow-x: auto; border-radius: 0; white-space: pre-wrap; word-break: break-word; max-width: 100%;"><code class="language-php">{{ $error['stack_trace'] }}</code></pre>
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge bg-secondary">PHP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Raw Error Data -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-database me-2"></i>
                                    البيانات الخام (JSON)
                                </h5>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-light btn-sm" onclick="copyRawData()">
                                        <i class="fas fa-copy me-1"></i>نسخ JSON
                                    </button>
                                    <button class="btn btn-outline-light btn-sm" onclick="formatJson()">
                                        <i class="fas fa-code me-1"></i>تنسيق
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <pre class="mb-0 hljs" style="max-height: 400px; overflow-y: auto; overflow-x: auto; border-radius: 0; white-space: pre-wrap; word-break: break-word; max-width: 100%;"><code class="language-json">{{ json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-info">JSON</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Copy Modal -->
<div class="modal fade" id="copyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نسخ تفاصيل الخطأ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="errorDetails" class="form-control" rows="20" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" onclick="copyToClipboard()">نسخ إلى الحافظة</button>
            </div>
        </div>
    </div>
</div>

<!-- Highlight.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/json.min.js"></script>

<script>
// Initialize syntax highlighting
document.addEventListener('DOMContentLoaded', function() {
    hljs.highlightAll();
});

function copyErrorDetails() {
    const error = @json($error);
    
    let errorText = `=== تفاصيل الخطأ ===\n\n`;
    errorText += `التاريخ: ${error.date}\n`;
    errorText += `المستوى: ${error.level}\n`;
    errorText += `الرسالة: ${error.message}\n\n`;
    
    if (error.context && error.context.length > 0) {
        errorText += `=== معلومات السياق ===\n`;
        error.context.forEach(ctx => {
            errorText += `- ${ctx}\n`;
        });
        errorText += `\n`;
    }
    
    if (error.stack_trace) {
        errorText += `=== Stack Trace ===\n${error.stack_trace}\n\n`;
    }
    
    errorText += `=== البيانات الخام ===\n`;
    errorText += JSON.stringify(error, null, 2);
    
    document.getElementById('errorDetails').value = errorText;
    new bootstrap.Modal(document.getElementById('copyModal')).show();
}

function copyErrorMessage() {
    const error = @json($error);
    navigator.clipboard.writeText(error.message).then(() => {
        showToast('تم نسخ رسالة الخطأ بنجاح!', 'success');
    });
}

function copyStackTrace() {
    const error = @json($error);
    navigator.clipboard.writeText(error.stack_trace).then(() => {
        showToast('تم نسخ Stack Trace بنجاح!', 'success');
    });
}

function copyRawData() {
    const error = @json($error);
    const jsonData = JSON.stringify(error, null, 2);
    navigator.clipboard.writeText(jsonData).then(() => {
        showToast('تم نسخ البيانات الخام بنجاح!', 'success');
    });
}

function toggleStackTrace() {
    const stackTraceSection = document.getElementById('stack-trace-section');
    const pre = stackTraceSection.querySelector('pre');
    
    if (pre.style.maxHeight === 'none') {
        pre.style.maxHeight = '500px';
        event.target.innerHTML = '<i class="fas fa-expand me-1"></i>توسيع';
    } else {
        pre.style.maxHeight = 'none';
        event.target.innerHTML = '<i class="fas fa-compress me-1"></i>تصغير';
    }
}

function toggleSection(sectionId) {
    const section = document.getElementById(sectionId + '-section');
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function formatJson() {
    const error = @json($error);
    const formatted = JSON.stringify(error, null, 4);
    const codeElement = document.querySelector('.language-json');
    codeElement.textContent = formatted;
    hljs.highlightElement(codeElement);
    showToast('تم تنسيق JSON بنجاح!', 'info');
}

function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
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
