<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب - {{ $order->order_number }}</title>
    <style>
        /* Import Cairo font with proper Arabic support */
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            direction: rtl;
            unicode-bidi: embed;
        }
        
        body {
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            line-height: 1.6;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Container */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 10px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .invoice-number {
            font-size: 20px;
            color: #6b7280;
            font-weight: 500;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        /* Order Info Grid */
        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .info-section h3 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
        }
        
        .info-item {
            margin-bottom: 10px;
            color: #4b5563;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .info-value {
            font-weight: 400;
            color: #1f2937;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .items-table th {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 15px 12px;
            text-align: right;
            font-weight: 600;
            font-size: 16px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .items-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            vertical-align: top;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .items-table tr:hover {
            background-color: #f3f4f6;
        }
        
        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 25px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #cbd5e1;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .total-section h3 {
            color: #1f2937;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
            text-align: center;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 0;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .total-row.final {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            border-top: 3px solid #2563eb;
            padding-top: 15px;
            margin-top: 15px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            padding: 15px;
            border-radius: 6px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .status-confirmed {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-shipped {
            background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
            color: #1e40af;
            border: 1px solid #3b82f6;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        .footer p {
            margin-bottom: 8px;
            font-family: 'Cairo', 'Tajawal', 'Arial', sans-serif;
        }
        
        /* RTL Specific */
        [dir="rtl"] {
            text-align: right;
            direction: rtl;
        }
        
        [dir="rtl"] .items-table th,
        [dir="rtl"] .items-table td {
            text-align: right;
        }
        
        [dir="rtl"] .total-row {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .info-item {
            flex-direction: row-reverse;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                padding: 20px;
                margin: 0;
                max-width: none;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
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
                    <span class="info-value">{{ $order->user->name ?? 'غير محدد' }}</span>
                    <span class="info-label">الاسم:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">{{ $order->user->email ?? 'غير محدد' }}</span>
                    <span class="info-label">البريد الإلكتروني:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">{{ $order->user->phone ?? 'غير محدد' }}</span>
                    <span class="info-label">رقم الهاتف:</span>
                </div>
            </div>

            <div class="info-section">
                <h3>معلومات التوصيل</h3>
                <div class="info-item">
                    <span class="info-value">{{ $order->delivery_address }}</span>
                    <span class="info-label">العنوان:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">{{ $order->delivery_city }}</span>
                    <span class="info-label">المدينة:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">{{ $order->delivery_governorate }}</span>
                    <span class="info-label">المحافظة:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">{{ $order->delivery_phone }}</span>
                    <span class="info-label">هاتف التوصيل:</span>
                </div>
                <div class="info-item">
                    <span class="info-value">
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    </span>
                    <span class="info-label">حالة الطلب:</span>
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
            <h3>ملخص الطلب</h3>
            <div class="total-row">
                <span>{{ number_format($order->subtotal, 3) }} د.ك</span>
                <span>المجموع الفرعي:</span>
            </div>
            <div class="total-row">
                <span>{{ number_format($order->delivery_fee, 3) }} د.ك</span>
                <span>رسوم التوصيل:</span>
            </div>
            <div class="total-row">
                <span>{{ number_format($order->tax_amount, 3) }} د.ك</span>
                <span>ضريبة القيمة المضافة (15%):</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>-{{ number_format($order->discount_amount, 3) }} د.ك</span>
                <span>الخصم:</span>
            </div>
            @endif
            <div class="total-row final">
                <span>{{ number_format($order->total_amount, 3) }} د.ك</span>
                <span>المجموع الكلي:</span>
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
