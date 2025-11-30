@extends('admin.layouts.app')

@section('title', 'صيانة النظام')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tools"></i> صيانة النظام
        </h1>
    </div>

    {{-- رسائل النجاح والخطأ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- معلومات النظام --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> معلومات النظام</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-2"><strong>إصدار PHP:</strong></p>
                            <p class="text-muted">{{ $systemInfo['php_version'] }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>إصدار Laravel:</strong></p>
                            <p class="text-muted">{{ $systemInfo['laravel_version'] }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Cache Driver:</strong></p>
                            <p class="text-muted">{{ $systemInfo['cache_driver'] }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Session Driver:</strong></p>
                            <p class="text-muted">{{ $systemInfo['session_driver'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- أحجام المخلفات --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-memory fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Cache</div>
                            <div class="fs-5 fw-bold">{{ $cacheSizes['cache'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-eye fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Views</div>
                            <div class="fs-5 fw-bold">{{ $cacheSizes['views'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Sessions</div>
                            <div class="fs-5 fw-bold">{{ $cacheSizes['sessions'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Logs</div>
                            <div class="fs-5 fw-bold">{{ $cacheSizes['logs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- أدوات التنظيف --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-broom"></i> أدوات التنظيف</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-all-cache') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من مسح جميع المخلفات؟')">
                                    <i class="fas fa-trash-alt"></i> مسح جميع المخلفات
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">مسح Cache, Config, Routes, Views</small>
                        </div>
                        
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-cache') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-memory"></i> مسح Cache فقط
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">مسح كاش التطبيق</small>
                        </div>
                        
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-config') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-cog"></i> مسح Config Cache
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">مسح كاش الإعدادات</small>
                        </div>
                        
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-routes') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-route"></i> مسح Route Cache
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">مسح كاش الروتات</small>
                        </div>
                        
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-views') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-eye"></i> مسح View Cache
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">مسح كاش الـ Views</small>
                        </div>
                        
                        <div class="col-md-4">
                            <form action="{{ route('admin.system.clear-logs') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من حذف جميع ملفات الـ Logs؟')">
                                    <i class="fas fa-file-alt"></i> مسح Logs
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">حذف جميع ملفات الـ Log</small>
                        </div>
                        
                        <div class="col-md-6">
                            <form action="{{ route('admin.system.clear-sessions') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('هل أنت متأكد من مسح جميع الـ Sessions؟')">
                                    <i class="fas fa-user-clock"></i> مسح Sessions
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">حذف جميع الجلسات النشطة</small>
                        </div>
                        
                        <div class="col-md-6">
                            <form action="{{ route('admin.system.optimize') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-tachometer-alt"></i> تحسين النظام
                                </button>
                            </form>
                            <small class="text-muted d-block mt-2">تحسين وإعادة بناء Cache</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ملفات الـ Logs --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> ملفات الـ Logs</h5>
                </div>
                <div class="card-body">
                    @if(count($logFiles) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم الملف</th>
                                        <th>الحجم</th>
                                        <th>آخر تعديل</th>
                                        <th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logFiles as $log)
                                        <tr>
                                            <td><code>{{ $log['name'] }}</code></td>
                                            <td>{{ $log['size'] }}</td>
                                            <td>{{ $log['modified'] }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.system.view-log', $log['name']) }}" class="btn btn-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.system.download-log', $log['name']) }}" class="btn btn-success" title="تنزيل">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <form action="{{ route('admin.system.delete-log', $log['name']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا الملف؟')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">لا توجد ملفات Logs</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- API Routes --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-route"></i> API Routes ({{ count($apiRoutes) }} route)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="routeSearch" class="form-control" placeholder="البحث في الروتات...">
                    </div>
                    
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-sm table-hover" id="routesTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="10%">Method</th>
                                    <th width="30%">URI</th>
                                    <th width="20%">Name</th>
                                    <th width="25%">Action</th>
                                    <th width="15%">Middleware</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apiRoutes as $route)
                                    <tr class="route-row">
                                        <td>
                                            @foreach(explode('|', $route['methods']) as $method)
                                                @if($method == 'GET')
                                                    <span class="badge bg-success">{{ $method }}</span>
                                                @elseif($method == 'POST')
                                                    <span class="badge bg-primary">{{ $method }}</span>
                                                @elseif($method == 'PUT' || $method == 'PATCH')
                                                    <span class="badge bg-warning">{{ $method }}</span>
                                                @elseif($method == 'DELETE')
                                                    <span class="badge bg-danger">{{ $method }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $method }}</span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td><code class="text-dark">{{ $route['uri'] }}</code></td>
                                        <td><small class="text-muted">{{ $route['name'] ?: '-' }}</small></td>
                                        <td><small class="text-muted">{{ Str::limit($route['action'], 50) }}</small></td>
                                        <td><small class="text-muted">{{ Str::limit($route['middleware'], 30) }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
</style>

<script>
    // البحث في الروتات
    document.getElementById('routeSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('#routesTable .route-row');
        
        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection

