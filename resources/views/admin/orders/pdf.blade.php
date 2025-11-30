<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة الطلب #{{ $order->order_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .info-value {
            color: #2c3e50;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background: #f39c12; color: white; }
        .status-confirmed { background: #3498db; color: white; }
        .status-preparing { background: #9b59b6; color: white; }
        .status-ready { background: #2ecc71; color: white; }
        .status-shipped { background: #1abc9c; color: white; }
        .status-delivered { background: #27ae60; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
        .payment-paid { background: #2ecc71; color: white; }
        .payment-pending { background: #f39c12; color: white; }
        .payment-failed { background: #e74c3c; color: white; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .items-table thead {
            background: #3498db;
            color: white;
        }
        
        .items-table th {
            padding: 15px;
            text-align: right;
            font-weight: 600;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .total-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .total-summary h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .total-row:last-child {
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 10px;
            margin-top: 10px;
            font-weight: 700;
            font-size: 16px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
        
        @media print {
            body { margin: 0; }
            .container { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>فاتورة الطلب #{{ $order->order_number }}</h1>
            <p>تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <!-- Customer and Vendor Info -->
        <div class="info-grid">
            <div class="info-card">
                <h3>معلومات العميل</h3>
                <div class="info-item">
                    <span class="info-label">الاسم:</span>
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
            
            <div class="info-card">
                <h3>معلومات المتجر</h3>
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
                <div class="info-item">
                    <span class="info-label">حالة الطلب:</span>
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <h3 style="margin-bottom: 15px; color: #2c3e50;">عناصر الطلب</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: center;">{{ number_format($item->unit_price, 3) }} د.ك</td>
                    <td style="text-align: center;">{{ number_format($item->total_price, 3) }} د.ك</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Order Summary -->
        <div class="total-summary">
            <h3>ملخص الطلب</h3>
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

        <!-- Footer -->
        <div class="footer">
            <p>تم إنشاء هذه الفاتورة في {{ now()->format('Y-m-d H:i') }}</p>
            <p>شكراً لاختياركم خدماتنا</p>
        </div>
    </div>
</body>
</html>
