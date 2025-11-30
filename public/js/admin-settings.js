// تحسين معاينة الصورة في إعدادات الأدمن
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById('preview-img-' + input.name);
    const validationDiv = document.getElementById('file-validation-' + input.name);
    const validationMessage = document.getElementById('validation-message-' + input.name);
    
    // إخفاء رسائل التحقق السابقة
    if (validationDiv) {
        validationDiv.style.display = 'none';
    }
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // التحقق من نوع الملف
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showValidationError(input.name, 'نوع الملف غير مدعوم. يرجى اختيار صورة بصيغة JPG, PNG, GIF, SVG, أو WEBP');
            input.value = '';
            return;
        }
        
        // التحقق من حجم الملف (5MB كحد أقصى)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            showValidationError(input.name, 'حجم الملف كبير جداً. الحد الأقصى هو 5 ميجابايت');
            input.value = '';
            return;
        }
        
        // إظهار معاينة الصورة
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) {
                previewImg.src = e.target.result;
                if (preview) {
                    preview.style.display = 'block';
                }
            }
            
            // تحديث تسمية الملف
            updateFileLabel(input, file.name);
            
            // إظهار معلومات الملف
            showFileInfo(input.name, file);
        }
        reader.readAsDataURL(file);
        
    } else {
        // إخفاء المعاينة إذا لم يتم اختيار ملف
        if (preview) {
            preview.style.display = 'none';
        }
        updateFileLabel(input, 'اختر صورة الشعار...');
    }
}

// إظهار رسالة خطأ في التحقق
function showValidationError(inputName, message) {
    const validationDiv = document.getElementById('file-validation-' + inputName);
    const validationMessage = document.getElementById('validation-message-' + inputName);
    
    if (validationDiv && validationMessage) {
        validationMessage.textContent = message;
        validationDiv.style.display = 'block';
    } else {
        alert(message);
    }
}

// تحديث تسمية الملف
function updateFileLabel(input, fileName) {
    const label = input.nextElementSibling;
    if (label && label.classList.contains('custom-file-label')) {
        label.textContent = fileName;
    }
}

// إظهار معلومات الملف
function showFileInfo(inputName, file) {
    const fileSize = (file.size / 1024).toFixed(2); // بالكيلوبايت
    const fileType = file.type;
    
    console.log(`معلومات الملف - ${inputName}:`, {
        name: file.name,
        size: fileSize + ' KB',
        type: fileType,
        lastModified: new Date(file.lastModified).toLocaleString('ar-SA')
    });
}

// تحسين رفع الملف مع شريط التقدم
function enhanceFileUpload() {
    const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            let hasFiles = false;
            
            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    hasFiles = true;
                }
            });
            
            if (hasFiles) {
                // إظهار رسالة التحميل
                showUploadProgress();
            }
        });
    });
}

// إظهار شريط التقدم
function showUploadProgress() {
    const progressHtml = `
        <div id="upload-progress" class="fixed-top bg-primary text-white p-3" style="z-index: 9999;">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-3" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <span>جاري رفع الصورة... يرجى الانتظار</span>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', progressHtml);
}

// تهيئة التحسينات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    enhanceFileUpload();
    
    // تحسين عرض الصور الحالية
    const currentImages = document.querySelectorAll('[id^="current-"]');
    currentImages.forEach(container => {
        const img = container.querySelector('img');
        if (img) {
            img.addEventListener('error', function() {
                this.src = '/images/placeholder-logo.png';
                this.alt = 'صورة غير متوفرة';
            });
            
            // إضافة إمكانية النقر للتكبير
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                showImageModal(this.src, this.alt);
            });
        }
    });
});

// عرض الصورة في نافذة منبثقة
function showImageModal(src, alt) {
    const modalHtml = `
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${alt}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${src}" alt="${alt}" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // إزالة النافذة المنبثقة السابقة إن وجدت
    const existingModal = document.getElementById('imageModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // إضافة النافذة المنبثقة الجديدة
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // إظهار النافذة المنبثقة
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
    
    // إزالة النافذة المنبثقة بعد إغلاقها
    document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// إعادة تعيين الإعدادات مع تأكيد محسن
function resetSettings() {
    const confirmHtml = `
        <div class="modal fade" id="resetConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle"></i> تأكيد إعادة التعيين
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">هل أنت متأكد من إعادة تعيين جميع الإعدادات في هذه المجموعة إلى القيم الافتراضية؟</p>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>تحذير:</strong> سيتم فقدان جميع التغييرات الحالية بما في ذلك الصور المرفوعة!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-warning" onclick="confirmReset()">
                            <i class="fas fa-undo"></i> نعم، إعادة تعيين
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', confirmHtml);
    const modal = new bootstrap.Modal(document.getElementById('resetConfirmModal'));
    modal.show();
}

// تأكيد إعادة التعيين
function confirmReset() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('resetConfirmModal'));
    modal.hide();
    
    // الحصول على المجموعة الحالية من الرابط
    const currentPath = window.location.pathname;
    const group = currentPath.split('/').pop();
    
    fetch(`/admin/settings/reset/${group}`, {
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
            alert('حدث خطأ: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    })
    .finally(() => {
        document.getElementById('resetConfirmModal').remove();
    });
}
