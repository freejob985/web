<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب - {{ $order->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-pdf.css') }}">
    <style>
        /* Additional inline styles for PDF generation */
        @font-face {
            font-family: 'Cairo';
            src: url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');
            font-display: swap;
        }
        
        @font-face {
            font-family: 'Tajawal';
            src: url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
            font-display: swap;
        }
        
        * {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
            direction: rtl !important;
            unicode-bidi: embed !important;
        }
        
        body {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
            direction: rtl !important;
            text-align: right !important;
            unicode-bidi: embed !important;
        }
        
        .invoice-container {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
        }
        
        .header, .logo, .invoice-title, .invoice-number {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
        }
        
        .info-section h3, .info-item, .info-label {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
        }
        
        .items-table th, .items-table td {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
            text-align: right !important;
        }
        
        .total-section, .total-row, .footer {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
        }
        
        .status-badge {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">إيليت ون سوبر ماركت</div>
            <div class="invoice-title">فاتورة الطلب</div>
            <div class="invoice-number">رقم الطلب: {{ $order->order_number }}</div>
        </div>

        <div class="order-info">
            <div class="info-section">
                <h3>معلومات العميل</h3>
                <div class="info-item">
                    <span class="info-label">الاسم:</span> {{ $order->user->name ?? 'غير محدد' }}
                </div>
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span> {{ $order->user->email ?? 'غير محدد' }}
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الهاتف:</span> {{ $order->user->phone ?? 'غير محدد' }}
                </div>
            </div>

            <div class="info-section">
                <h3>معلومات التوصيل</h3>
                <div class="info-item">
                    <span class="info-label">العنوان:</span> {{ $order->delivery_address }}
                </div>
                <div class="info-item">
                    <span class="info-label">المدينة:</span> {{ $order->delivery_city }}
                </div>
                <div class="info-item">
                    <span class="info-label">المحافظة:</span> {{ $order->delivery_governorate }}
                </div>
                <div class="info-item">
                    <span class="info-label">هاتف التوصيل:</span> {{ $order->delivery_phone }}
                </div>
                <div class="info-item">
                    <span class="info-label">حالة الطلب:</span> 
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>المجموع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        @if($item->type === 'offer' || $item->offer_id)
                            {{ $item->name ?? $item->product_name ?? 'عرض غير محدد' }}
                            <small style="color: #666;">(عرض)</small>
                        @else
                            {{ $item->product->name ?? $item->product_name ?? 'منتج غير محدد' }}
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price ?? $item->unit_price, 3) }} د.ك</td>
                    <td>{{ number_format(($item->total ?? ($item->price ?? $item->unit_price) * $item->quantity), 3) }} د.ك</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($order->subtotal, 3) }} د.ك</span>
            </div>
            <div class="total-row">
                <span>رسوم التوصيل:</span>
                <span>{{ number_format($order->delivery_fee, 3) }} د.ك</span>
            </div>
            <div class="total-row">
                <span>ضريبة القيمة المضافة (15%):</span>
                <span>{{ number_format($order->tax_amount, 3) }} د.ك</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>الخصم:</span>
                <span>-{{ number_format($order->discount_amount, 3) }} د.ك</span>
            </div>
            @endif
            <div class="total-row final">
                <span>المجموع الكلي:</span>
                <span>{{ number_format($order->total_amount, 3) }} د.ك</span>
            </div>
        </div>

        <div class="footer">
            <p>شكراً لك لاختيارك إيليت ون سوبر ماركت</p>
            <p>تاريخ إصدار الفاتورة: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p>للاستفسارات، يرجى التواصل معنا على: info@elite1market.com</p>
        </div>
    </div>
</body>
</html>