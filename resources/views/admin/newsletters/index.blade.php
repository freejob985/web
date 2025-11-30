@extends('admin.layouts.app')

@section('title', 'إدارة النشرة الإخبارية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">المشتركين في النشرة الإخبارية</h3>
                    <div>
                        <a href="{{ route('admin.newsletters.campaigns') }}" class="btn btn-info">
                            <i class="fas fa-envelope"></i> الحملات
                        </a>
                        <a href="{{ route('admin.newsletters.campaigns.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> حملة جديدة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشط</option>
                                <option value="unsubscribed">ملغي الاشتراك</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput" placeholder="البحث بالبريد الإلكتروني أو الاسم...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary" onclick="applyFilters()">تصفية</button>
                        </div>
                        <div class="col-md-3 text-right">
                            <button class="btn btn-success" onclick="exportSubscribers()">
                                <i class="fas fa-download"></i> تصدير
                            </button>
                        </div>
                    </div>

                    <!-- Subscribers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>البريد الإلكتروني</th>
                                    <th>الاسم</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الاشتراك</th>
                                    <th>تاريخ إلغاء الاشتراك</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ $subscriber->name ?: 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $subscriber->status == 'active' ? 'success' : 'danger' }}">
                                            {{ $subscriber->status == 'active' ? 'نشط' : 'ملغي الاشتراك' }}
                                        </span>
                                    </td>
                                    <td>{{ $subscriber->subscribed_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($subscriber->unsubscribed_at)
                                            {{ $subscriber->unsubscribed_at->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($subscriber->status == 'active')
                                                <button class="btn btn-sm btn-warning" onclick="unsubscribe({{ $subscriber->id }})">
                                                    <i class="fas fa-ban"></i> إلغاء الاشتراك
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success" onclick="resubscribe({{ $subscriber->id }})">
                                                    <i class="fas fa-check"></i> إعادة الاشتراك
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST" 
                                                  style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا يوجد مشتركين</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function unsubscribe(subscriberId) {
    if (confirm('هل أنت متأكد من إلغاء الاشتراك؟')) {
        fetch(`/admin/newsletters/${subscriberId}/unsubscribe`, {
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

function resubscribe(subscriberId) {
    if (confirm('هل أنت متأكد من إعادة الاشتراك؟')) {
        fetch(`/admin/newsletters/${subscriberId}/resubscribe`, {
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

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    window.location.href = '{{ route("admin.newsletters.index") }}?' + params.toString();
}

function exportSubscribers() {
    window.location.href = '{{ route("admin.newsletters.index") }}?export=1';
}
</script>
@endsection
