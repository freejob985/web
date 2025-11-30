@extends('admin.layouts.app')

@section('title', 'إنشاء حملة جديدة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إنشاء حملة جديدة</h3>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.newsletters.campaigns.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject">موضوع الرسالة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content">محتوى الرسالة <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                يمكنك استخدام HTML في المحتوى. سيتم إرسال الرسالة مع التصميم الافتراضي.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="html_content">محتوى HTML (اختياري)</label>
                            <textarea class="form-control @error('html_content') is-invalid @enderror" 
                                      id="html_content" name="html_content" rows="15">{{ old('html_content') }}</textarea>
                            @error('html_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                إذا تم ملء هذا الحقل، سيتم استخدامه بدلاً من المحتوى العادي.
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_at">جدولة الإرسال (اختياري)</label>
                                    <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        إذا لم يتم تحديد وقت، سيتم الإرسال فوراً.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="form-group">
                            <button type="button" class="btn btn-info" onclick="previewEmail()">
                                <i class="fas fa-eye"></i> معاينة الرسالة
                            </button>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">حفظ الحملة</button>
                            <a href="{{ route('admin.newsletters.campaigns') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">معاينة الرسالة</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="emailPreview" style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewEmail() {
    const subject = document.getElementById('subject').value;
    const content = document.getElementById('content').value;
    const htmlContent = document.getElementById('html_content').value;
    
    if (!subject || !content) {
        alert('يرجى ملء الموضوع والمحتوى أولاً');
        return;
    }
    
    // Create preview content
    let previewContent = `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div style="background: linear-gradient(135deg, #3B82F6, #10B981); color: white; padding: 30px 20px; text-align: center;">
                <h1 style="margin: 0; font-size: 28px; font-weight: bold;">إنقب</h1>
                <p style="margin: 5px 0 0 0;">منصة التسوق الإلكتروني الرائدة في الكويت</p>
            </div>
            
            <div style="padding: 30px 20px;">
                <h2 style="color: #3B82F6; margin-top: 0; font-size: 24px;">${subject}</h2>
                
                <div style="margin: 20px 0;">
                    ${htmlContent ? htmlContent : content.replace(/\n/g, '<br>')}
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="#" style="display: inline-block; background: linear-gradient(135deg, #3B82F6, #10B981); color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">تسوق الآن</a>
                </div>
            </div>
            
            <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
                <p style="margin: 5px 0; font-size: 14px; color: #6c757d;"><strong>إنقب</strong></p>
                <p style="margin: 5px 0; font-size: 14px; color: #6c757d;">منصة التسوق الإلكتروني الرائدة في الكويت</p>
                <p style="margin: 5px 0; font-size: 14px; color: #6c757d;">📞 +965 1234 5678 | ✉️ info@engeb.com</p>
            </div>
        </div>
    `;
    
    document.getElementById('emailPreview').innerHTML = previewContent;
    $('#previewModal').modal('show');
}
</script>
@endsection
