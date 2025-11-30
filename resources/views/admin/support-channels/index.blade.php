@extends('admin.layouts.app')

@section('title', 'قنوات الدعم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">قنوات الدعم</h3>
                    <a href="{{ route('admin.support-channels.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة قناة دعم جديدة
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($supportChannels->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العنوان</th>
                                        <th>معلومات التواصل</th>
                                        <th>ساعات العمل</th>
                                        <th>الترتيب</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supportChannels as $channel)
                                        <tr>
                                            <td>{{ $channel->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($channel->icon)
                                                        <i class="{{ $channel->icon }} me-2" style="color: {{ $channel->color }}"></i>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $channel->title_ar }}</strong>
                                                        @if($channel->title_en)
                                                            <br><small class="text-muted">{{ $channel->title_en }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $channel->contact_info }}</td>
                                            <td>{{ $channel->availability }}</td>
                                            <td>{{ $channel->sort_order }}</td>
                                            <td>
                                                <button class="btn btn-sm toggle-status {{ $channel->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                        data-id="{{ $channel->id }}"
                                                        data-status="{{ $channel->is_active ? '1' : '0' }}">
                                                    {{ $channel->is_active ? 'مفعل' : 'غير مفعل' }}
                                                </button>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.support-channels.show', $channel) }}" 
                                                       class="btn btn-info btn-sm" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.support-channels.edit', $channel) }}" 
                                                       class="btn btn-warning btn-sm" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.support-channels.destroy', $channel) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه القناة؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="حذف">
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
                        <div class="text-center py-5">
                            <i class="fas fa-headset fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد قنوات دعم</h5>
                            <p class="text-muted">ابدأ بإضافة قناة دعم جديدة</p>
                            <a href="{{ route('admin.support-channels.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إضافة قناة دعم
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        const channelId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const button = $(this);
        
        $.ajax({
            url: `/admin/support-channels/${channelId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_active) {
                        button.removeClass('btn-secondary').addClass('btn-success').text('مفعل');
                        button.data('status', '1');
                    } else {
                        button.removeClass('btn-success').addClass('btn-secondary').text('غير مفعل');
                        button.data('status', '0');
                    }
                    
                    // Show success message
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('حدث خطأ أثناء تحديث الحالة');
            }
        });
    });
});
</script>
@endpush
