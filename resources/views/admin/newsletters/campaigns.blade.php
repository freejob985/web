@extends('admin.layouts.app')

@section('title', 'حملات النشرة الإخبارية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">حملات النشرة الإخبارية</h3>
                    <a href="{{ route('admin.newsletters.campaigns.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> حملة جديدة
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Campaigns Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>الموضوع</th>
                                    <th>الحالة</th>
                                    <th>إجمالي المرسل إليهم</th>
                                    <th>تم الإرسال</th>
                                    <th>فشل الإرسال</th>
                                    <th>التقدم</th>
                                    <th>تاريخ الإرسال</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->subject }}</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $campaign->status == 'sent' ? 'success' : 
                                            ($campaign->status == 'sending' ? 'warning' : 
                                            ($campaign->status == 'failed' ? 'danger' : 'secondary')) 
                                        }}">
                                            {{ 
                                                $campaign->status == 'sent' ? 'تم الإرسال' : 
                                                ($campaign->status == 'sending' ? 'جاري الإرسال' : 
                                                ($campaign->status == 'failed' ? 'فشل' : 'مسودة')) 
                                            }}
                                        </span>
                                    </td>
                                    <td>{{ $campaign->total_recipients }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ $campaign->sent_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">{{ $campaign->failed_count }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $campaign->progress_percentage }}%"
                                                 aria-valuenow="{{ $campaign->progress_percentage }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $campaign->progress_percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($campaign->sent_at)
                                            {{ $campaign->sent_at->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">لم يتم الإرسال</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.newsletters.campaigns.show', $campaign) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($campaign->status == 'draft')
                                                <a href="{{ route('admin.newsletters.campaigns.create') }}?edit={{ $campaign->id }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-success" onclick="sendCampaign({{ $campaign->id }})">
                                                    <i class="fas fa-paper-plane"></i> إرسال
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد حملات</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $campaigns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendCampaign(campaignId) {
    if (confirm('هل أنت متأكد من إرسال هذه الحملة؟')) {
        fetch(`/admin/newsletters/campaigns/${campaignId}/send`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال الحملة بنجاح: ' + data.message);
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
</script>
@endsection
