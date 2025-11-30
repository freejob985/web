@extends('admin.layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
    
    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .order-info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
    .status-confirmed { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
    .status-preparing { background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; }
    .status-ready { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; }
    .status-shipped { background: linear-gradient(135deg, #1abc9c, #16a085); color: white; }
    .status-delivered { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; }
    .status-cancelled { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    
    .payment-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: 500;
        font-size: 0.8rem;
    }
    
    .payment-paid { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; }
    .payment-pending { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
    .payment-failed { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    
    .product-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .total-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-top: 20px;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .total-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.2rem;
        margin-top: 10px;
        padding-top: 15px;
        border-top: 2px solid rgba(255,255,255,0.3);
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-export {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        color: white;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .info-section h5 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 3px solid #3498db;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
    }
    
    .info-value {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .arabic-font {
        font-family: 'Cairo', 'Tajawal', sans-serif;
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Order Header -->
    <div class="order-header arabic-font">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-receipt me-3"></i>
                    تفاصيل الطلب #{{ $order->order_number }}
                </h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar me-2"></i>
                    {{ $order->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="action-buttons">
                    <a href="{{ route('admin.orders.pdf', $order) }}" class="btn btn-export" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>تصدير PDF
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
</div>

@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
@endif

    <div class="info-grid">
        <!-- Order Status & Actions -->
        <div class="order-info-card arabic-font">
            <h5><i class="fas fa-cogs me-2"></i>حالة الطلب والإجراءات</h5>
            
            <div class="info-item">
                <span class="info-label">الحالة الحالية:</span>
                <span class="status-badge status-{{ $order->status }}">
                    {{ $order->status_label }}
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">حالة الدفع:</span>
                <span class="payment-badge payment-{{ $order->payment_status }}">
                    {{ $order->payment_status_label }}
                </span>
            </div>
            
            <hr class="my-3">
            
            <!-- Status Update Form -->
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mb-3">
      @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">تغيير الحالة:</label>
                    <select name="status" class="form-select" required>
                        @foreach([
                            'pending' => 'في الانتظار',
                            'confirmed' => 'مؤكد',
                            'preparing' => 'قيد التحضير',
                            'ready' => 'جاهز للتوصيل',
                            'shipped' => 'تم الشحن',
                            'delivered' => 'تم التوصيل',
                            'cancelled' => 'ملغي'
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
                
                <div class="mb-3">
                    <label class="form-label">ملاحظات:</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="أضف ملاحظات حول تغيير الحالة..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i>حفظ التغييرات
                </button>
    </form>

            @if(!in_array($order->status, ['delivered', 'cancelled']))
                <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
      @csrf
                    <div class="mb-3">
                        <label class="form-label">سبب الإلغاء:</label>
                        <input type="text" name="reason" class="form-control" placeholder="أدخل سبب الإلغاء (اختياري)">
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-times me-2"></i>إلغاء الطلب
                    </button>
    </form>
    @endif
  </div>

        <!-- Customer Information -->
        <div class="order-info-card arabic-font">
            <h5><i class="fas fa-user me-2"></i>معلومات العميل</h5>
            
            <div class="info-item">
                <span class="info-label">اسم العميل:</span>
                <span class="info-value">{{ optional($order->user)->name ?? 'غير محدد' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">{{ optional($order->user)->email ?? 'غير محدد' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">هاتف التوصيل:</span>
                <span class="info-value">{{ $order->delivery_phone }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">عنوان التوصيل:</span>
                <span class="info-value">{{ $order->delivery_address }}</span>
            </div>
        </div>

        <!-- Vendor Information -->
        <div class="order-info-card arabic-font">
            <h5><i class="fas fa-store me-2"></i>معلومات المتجر</h5>
            
            <div class="info-item">
                <span class="info-label">اسم المتجر:</span>
                <span class="info-value">{{ optional($order->vendor)->name ?? 'غير محدد' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">هاتف المتجر:</span>
                <span class="info-value">{{ optional($order->vendor)->phone ?? 'غير محدد' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">عنوان المتجر:</span>
                <span class="info-value">{{ optional($order->vendor)->address ?? 'غير محدد' }}</span>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="order-info-card arabic-font">
            <h5><i class="fas fa-credit-card me-2"></i>معلومات الدفع</h5>
            
            <div class="info-item">
                <span class="info-label">طريقة الدفع:</span>
                <span class="info-value">{{ $order->payment_method }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">حالة الدفع:</span>
                <span class="payment-badge payment-{{ $order->payment_status }}">
                    {{ $order->payment_status_label }}
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">تاريخ الدفع:</span>
                <span class="info-value">
                    {{ $order->paid_at ? $order->paid_at->format('Y-m-d H:i') : 'لم يتم الدفع بعد' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-info-card arabic-font">
        <h5><i class="fas fa-shopping-cart me-2"></i>عناصر الطلب</h5>
        
        <div class="row">
            @foreach($order->items as $item)
                <div class="col-md-6 mb-3">
                    <div class="product-item">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                <p class="text-muted mb-1">الكمية: {{ $item->quantity }}</p>
                                <p class="text-muted mb-0">السعر: {{ number_format($item->unit_price, 3) }} د.ك</p>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="text-primary mb-0">{{ number_format($item->total_price, 3) }} د.ك</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Order Summary -->
    <div class="total-summary arabic-font">
        <h5 class="mb-4"><i class="fas fa-calculator me-2"></i>ملخص الطلب</h5>
        
        <div class="total-row">
            <span>المجموع الفرعي:</span>
            <span>{{ number_format($order->subtotal, 3) }} د.ك</span>
        </div>
        
        @if($order->delivery_fee > 0)
        <div class="total-row">
            <span>رسوم التوصيل:</span>
            <span>{{ number_format($order->delivery_fee, 3) }} د.ك</span>
        </div>
        @endif
        
        @if($order->tax_amount > 0)
        <div class="total-row">
            <span>الضريبة:</span>
            <span>{{ number_format($order->tax_amount, 3) }} د.ك</span>
        </div>
        @endif
        
        @if($order->discount_amount > 0)
        <div class="total-row">
            <span>الخصم:</span>
            <span>-{{ number_format($order->discount_amount, 3) }} د.ك</span>
        </div>
        @endif
        
        <div class="total-row">
            <span>المجموع الكلي:</span>
            <span>{{ number_format($order->total_amount, 3) }} د.ك</span>
        </div>
    </div>
</div>

<!-- PDF Export Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function exportToPDF() {
    // Show loading
    showGlobalLoading();
    
    // Create a temporary div with the content to export
    const printContent = document.createElement('div');
    printContent.innerHTML = `
        <div style="font-family: 'Cairo', 'Tajawal', sans-serif; direction: rtl; padding: 20px; background: white;">
            <div style="text-align: center; margin-bottom: 30px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px;">
                <h1 style="margin: 0; font-size: 24px;">فاتورة الطلب #{{ $order->order_number }}</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h3 style="color: #2c3e50; margin-bottom: 15px;">معلومات العميل</h3>
                    <p><strong>الاسم:</strong> {{ optional($order->user)->name ?? 'غير محدد' }}</p>
                    <p><strong>البريد:</strong> {{ optional($order->user)->email ?? 'غير محدد' }}</p>
                    <p><strong>الهاتف:</strong> {{ $order->delivery_phone }}</p>
                    <p><strong>العنوان:</strong> {{ $order->delivery_address }}</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h3 style="color: #2c3e50; margin-bottom: 15px;">معلومات المتجر</h3>
                    <p><strong>اسم المتجر:</strong> {{ optional($order->vendor)->name ?? 'غير محدد' }}</p>
                    <p><strong>الهاتف:</strong> {{ optional($order->vendor)->phone ?? 'غير محدد' }}</p>
                    <p><strong>العنوان:</strong> {{ optional($order->vendor)->address ?? 'غير محدد' }}</p>
                </div>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2c3e50; margin-bottom: 15px;">عناصر الطلب</h3>
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <thead style="background: #3498db; color: white;">
                        <tr>
                            <th style="padding: 15px; text-align: right;">المنتج</th>
                            <th style="padding: 15px; text-align: center;">الكمية</th>
                            <th style="padding: 15px; text-align: center;">السعر</th>
                            <th style="padding: 15px; text-align: center;">الإجمالي</th>
        </tr>
      </thead>
      <tbody>
                        @foreach($order->items as $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">{{ $item->product_name }}</td>
                            <td style="padding: 15px; text-align: center;">{{ $item->quantity }}</td>
                            <td style="padding: 15px; text-align: center;">{{ number_format($item->unit_price, 3) }} د.ك</td>
                            <td style="padding: 15px; text-align: center;">{{ number_format($item->total_price, 3) }} د.ك</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px;">
                <h3 style="margin-bottom: 15px;">ملخص الطلب</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($order->subtotal, 3) }} د.ك</span>
                </div>
                @if($order->delivery_fee > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>رسوم التوصيل:</span>
                    <span>{{ number_format($order->delivery_fee, 3) }} د.ك</span>
                </div>
                @endif
                @if($order->tax_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>الضريبة:</span>
                    <span>{{ number_format($order->tax_amount, 3) }} د.ك</span>
                </div>
                @endif
                @if($order->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>الخصم:</span>
                    <span>-{{ number_format($order->discount_amount, 3) }} د.ك</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; border-top: 2px solid rgba(255,255,255,0.3); padding-top: 10px; margin-top: 10px;">
                    <span>المجموع الكلي:</span>
                    <span>{{ number_format($order->total_amount, 3) }} د.ك</span>
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 12px;">
                <p>تم إنشاء هذه الفاتورة في {{ now()->format('Y-m-d H:i') }}</p>
                <p>شكراً لاختياركم خدماتنا</p>
  </div>
</div>
    `;
    
    document.body.appendChild(printContent);
    
    html2canvas(printContent, {
        scale: 2,
        useCORS: true,
        allowTaint: true
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        
        const imgWidth = 210;
        const pageHeight = 295;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        
        let position = 0;
        
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
        
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }
        
        pdf.save('order-{{ $order->order_number }}.pdf');
        
        // Clean up
        document.body.removeChild(printContent);
        hideGlobalLoading();
        
        // Show success message
        Toastify({
            text: "تم تصدير الفاتورة بنجاح",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#28a745",
            stopOnFocus: true
        }).showToast();
    }).catch(error => {
        console.error('Error generating PDF:', error);
        hideGlobalLoading();
        
        Toastify({
            text: "حدث خطأ في تصدير الفاتورة",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#dc3545",
            stopOnFocus: true
        }).showToast();
    });
}
</script>
@endsection
