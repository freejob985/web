<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3B82F6, #10B981);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #3B82F6;
            margin-top: 0;
            font-size: 24px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3B82F6, #10B981);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #3B82F6;
            text-decoration: none;
        }
        .unsubscribe {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
        }
        .unsubscribe a {
            color: #3B82F6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>إنقب</h1>
            <p>منصة التسوق الإلكتروني الرائدة في الكويت</p>
        </div>
        
        <div class="content">
            <h2>{{ $campaign->subject }}</h2>
            
            @if($campaign->html_content)
                {!! $campaign->html_content !!}
            @else
                <p>{!! nl2br(e($campaign->content)) !!}</p>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" class="cta-button">تسوق الآن</a>
            </div>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#">فيسبوك</a>
                <a href="#">تويتر</a>
                <a href="#">إنستغرام</a>
                <a href="#">واتساب</a>
            </div>
            
            <p><strong>إنقب</strong></p>
            <p>منصة التسوق الإلكتروني الرائدة في الكويت</p>
            <p>📞 +965 1234 5678 | ✉️ info@engeb.com</p>
            <p>الكويت - جميع المحافظات</p>
            
            <div class="unsubscribe">
                <p>إذا كنت لا ترغب في تلقي هذه الرسائل، يمكنك <a href="{{ config('app.url') }}/unsubscribe?email={{ $subscriber->email }}">إلغاء الاشتراك</a></p>
            </div>
        </div>
    </div>
</body>
</html>
