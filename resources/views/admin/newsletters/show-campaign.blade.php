@extends('admin.layouts.app')

@section('title', 'تفاصيل الحملة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل الحملة: {{ $campaign->subject }}</h3>
                    <div>
                        @if($campaign->status == 'draft')
                            <button class="btn btn-success" onclick="sendCampaign({{ $campaign->id }})">
                                <i class="fas fa-paper-plane"></i> إرسال الحملة
                            </button>
                        @endif
                        <a href="{{ route('admin.newsletters.campaigns') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> العودة
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Campaign Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معلومات الحملة</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>الموضوع:</strong></td>
                                            <td>{{ $campaign->subject }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحالة:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>إجمالي المرسل إليهم:</strong></td>
                                            <td>{{ $campaign->total_recipients }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>تم الإرسال بنجاح:</strong></td>
                                            <td>
                                                <span class="badge badge-success">{{ $campaign->sent_count }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>فشل الإرسال:</strong></td>
                                            <td>
                                                <span class="badge badge-danger">{{ $campaign->failed_count }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>نسبة التقدم:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>تاريخ الإنشاء:</strong></td>
                                            <td>{{ $campaign->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @if($campaign->sent_at)
                                        <tr>
                                            <td><strong>تاريخ الإرسال:</strong></td>
                                            <td>{{ $campaign->sent_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Campaign Content -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">محتوى الحملة</h5>
                                </div>
                                <div class="card-body">
                                    <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; max-height: 400px; overflow-y: auto;">
                                        <h4>{{ $campaign->subject }}</h4>
                                        <hr>
                                        @if($campaign->html_content)
                                            {!! $campaign->html_content !!}
                                        @else
                                            <div style="white-space: pre-wrap;">{{ $campaign->content }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sending Logs -->
                    @if($campaign->logs && $campaign->logs->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">سجل الإرسال</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>البريد الإلكتروني</th>
                                                    <th>الاسم</th>
                                                    <th>الحالة</th>
                                                    <th>رسالة الخطأ</th>
                                                    <th>وقت الإرسال</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campaign->logs as $log)
                                                <tr>
                                                    <td>{{ $log->newsletter->email }}</td>
                                                    <td>{{ $log->newsletter->name ?: 'غير محدد' }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ 
                                                            $log->status == 'sent' ? 'success' : 
                                                            ($log->status == 'failed' ? 'danger' : 'warning') 
                                                        }}">
                                                            {{ 
                                                                $log->status == 'sent' ? 'تم الإرسال' : 
                                                                ($log->status == 'failed' ? 'فشل' : 'مرفوض') 
                                                            }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($log->error_message)
                                                            <small class="text-danger">{{ $log->error_message }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $log->sent_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
