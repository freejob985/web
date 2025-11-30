<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب - {{ $order->order_number }}</title>
    <style>
        /* Base styles for Arabic PDF with system fonts */
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
            font-family: 'Tahoma', 'Arial', 'Helvetica', sans-serif;
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
            line-height: 1.6;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        
        /* Container */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
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
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .invoice-number {
            font-size: 20px;
            color: #6b7280;
            font-weight: 500;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        /* Order Info Grid */
        .order-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-section {
            display: table-cell;
            width: 50%;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .info-section:first-child {
            margin-left: 15px;
        }
        
        .info-section h3 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 8px;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .info-item {
            margin-bottom: 10px;
            color: #4b5563;
            display: table;
            width: 100%;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
            display: table-cell;
            width: 40%;
            text-align: right;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .info-value {
            font-weight: normal;
            color: #1f2937;
            display: table-cell;
            width: 60%;
            text-align: right;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .items-table th {
            background: #2563eb;
            color: white;
            padding: 15px 12px;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .items-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        /* Total Section */
        .total-section {
            background: #f8fafc;
            padding: 25px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #cbd5e1;
        }
        
        .total-section h3 {
            color: #1f2937;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            padding: 8px 0;
        }
        
        .total-label {
            display: table-cell;
            width: 70%;
            text-align: right;
            font-weight: normal;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .total-value {
            display: table-cell;
            width: 30%;
            text-align: left;
            font-weight: bold;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .total-row.final {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            border-top: 3px solid #2563eb;
            padding-top: 15px;
            margin-top: 15px;
            background: #dbeafe;
            padding: 15px;
            border-radius: 6px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            font-family: 'Tahoma', 'Arial', sans-serif;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-shipped {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }
        
        .status-delivered {
            background: #d1fae5;
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
        }
        
        .footer p {
            margin-bottom: 8px;
            font-family: 'Tahoma', 'Arial', sans-serif;
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
                    <td>{{ $item->product->name ?? 'منتج غير محدد' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 3) }} د.ك</td>
                    <td>{{ number_format($item->price * $item->quantity, 3) }} د.ك</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <h3>ملخص الطلب</h3>
            <div class="total-row">
                <span class="total-value">{{ number_format($order->subtotal, 3) }} د.ك</span>
                <span class="total-label">المجموع الفرعي:</span>
            </div>
            <div class="total-row">
                <span class="total-value">{{ number_format($order->delivery_fee, 3) }} د.ك</span>
                <span class="total-label">رسوم التوصيل:</span>
            </div>
            <div class="total-row">
                <span class="total-value">{{ number_format($order->tax_amount, 3) }} د.ك</span>
                <span class="total-label">ضريبة القيمة المضافة (15%):</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span class="total-value">-{{ number_format($order->discount_amount, 3) }} د.ك</span>
                <span class="total-label">الخصم:</span>
            </div>
            @endif
            <div class="total-row final">
                <span class="total-value">{{ number_format($order->total_amount, 3) }} د.ك</span>
                <span class="total-label">المجموع الكلي:</span>
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
