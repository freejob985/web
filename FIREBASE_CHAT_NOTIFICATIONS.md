# نظام إشعارات Firebase لشات الطلبات (Salla Delivery Chat)

## نظرة عامة
نظام كامل لإدارة رسائل الشات بين السائقين والعملاء مع إشعارات Firebase تلقائية عند استقبال رسالة جديدة.

## المميزات
- ✅ إرسال رسائل بين السائق والعميل
- ✅ إشعارات Firebase تلقائية للمستلم
- ✅ دعم إرفاق ملفات (صور، PDF، مستندات)
- ✅ تتبع حالة قراءة الرسائل
- ✅ عرض عدد الرسائل غير المقروءة
- ✅ حفظ Firebase tokens للمستخدمين والسائقين

## المتطلبات

### 1. تثبيت Firebase SDK لـ PHP
```bash
composer require kreait/firebase-php
```

### 2. إعداد Firebase
1. أنشئ مشروع Firebase من [Firebase Console](https://console.firebase.google.com/)
2. انتقل إلى Project Settings > Service Accounts
3. اضغط على "Generate New Private Key"
4. احفظ ملف JSON في `storage/app/firebase/credentials.json`

### 3. تحديث ملف .env
```env
FIREBASE_CREDENTIALS=storage/app/firebase/credentials.json
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
```

### 4. تشغيل الـ Migrations
```bash
php artisan migrate
```

## الـ API Endpoints

### Base URL
```
/api/v1/salla-delivery/chat
```

### 1. إرسال رسالة جديدة
**Endpoint:** `POST /send/{order_id}`

**Parameters:**
```json
{
  "message": "نص الرسالة",
  "sender_type": "user|driver|admin",
  "sender_id": 1,
  "attachment": "ملف مرفق (اختياري)"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "تم إرسال الرسالة بنجاح",
  "data": {
    "message_id": 1,
    "order_id": 123,
    "order_number": "ORD-2025-000001",
    "sender_type": "user",
    "message": "نص الرسالة",
    "attachment": "رابط المرفق",
    "created_at": "2025-11-29T14:30:00+00:00"
  }
}
```

**ملاحظة:** عند إرسال رسالة، سيتم إرسال إشعار Firebase تلقائياً للمستلم:
- إذا أرسل المستخدم → يستلم السائق إشعار
- إذا أرسل السائق → يستلم المستخدم إشعار

### 2. الحصول على جميع الرسائل
**Endpoint:** `GET /messages/{order_id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "order_id": 123,
    "order_number": "ORD-2025-000001",
    "total_messages": 5,
    "messages": [
      {
        "id": 1,
        "sender_type": "user",
        "sender_id": 10,
        "message": "مرحبا",
        "attachment": null,
        "is_read": true,
        "created_at": "2025-11-29T14:30:00+00:00"
      }
    ]
  }
}
```

### 3. تحديد الرسالة كمقروءة
**Endpoint:** `POST /mark-read/{message_id}`

**Response:**
```json
{
  "success": true,
  "message": "تم تحديد الرسالة كمقروءة",
  "data": {
    "message_id": 1,
    "is_read": true,
    "read_at": "2025-11-29T14:35:00+00:00"
  }
}
```

### 4. عدد الرسائل غير المقروءة
**Endpoint:** `GET /unread-count/{order_id}/{recipient_type}`

**Parameters:**
- `recipient_type`: `user` أو `driver`

**Response:**
```json
{
  "success": true,
  "data": {
    "order_id": 123,
    "recipient_type": "driver",
    "unread_count": 3
  }
}
```

### 5. تحديث Firebase Token
**Endpoint:** `POST /update-fcm-token`

**Parameters:**
```json
{
  "user_type": "user|driver",
  "user_id": 1,
  "fcm_token": "firebase_device_token_here"
}
```

**Response:**
```json
{
  "success": true,
  "message": "تم تحديث Firebase token بنجاح"
}
```

## أمثلة على الاستخدام

### مثال 1: إرسال رسالة من المستخدم للسائق
```bash
curl -X POST https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/send/123 \
  -H "Content-Type: application/json" \
  -d '{
    "message": "متى ستصل؟",
    "sender_type": "user",
    "sender_id": 10
  }'
```
**النتيجة:** سيستلم السائق إشعار Firebase فوري

### مثال 2: إرسال رسالة من السائق للمستخدم مع مرفق
```bash
curl -X POST https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/send/123 \
  -F "message=سأصل خلال 10 دقائق" \
  -F "sender_type=driver" \
  -F "sender_id=5" \
  -F "attachment=@location_screenshot.jpg"
```
**النتيجة:** سيستلم المستخدم إشعار Firebase فوري مع عرض الرسالة

### مثال 3: الحصول على الرسائل
```bash
curl https://0d2c7ff974.nxcli.io/api/v1/salla-delivery/chat/messages/123
```

## بنية قاعدة البيانات

### جدول order_messages
```sql
- id
- order_id (FK -> orders)
- sender_type (user|driver|admin)
- sender_id
- message (text)
- attachment (string, nullable)
- is_read (boolean)
- read_at (timestamp, nullable)
- created_at
- updated_at
```

### جدول drivers
```sql
- id
- name
- phone
- email
- license_number
- vehicle_type
- vehicle_number
- status (active|inactive|busy)
- fcm_token (Firebase token)
- created_at
- updated_at
```

### تحديثات جدول users
```sql
+ fcm_token (Firebase token)
```

### تحديثات جدول orders
```sql
+ driver_id (FK -> drivers)
```

## الملفات المُنشأة

### Models
- `app/Models/OrderMessage.php` - نموذج رسائل الطلبات
- `app/Models/Driver.php` - نموذج السائقين

### Controllers
- `app/Http/Controllers/Api/SallaDeliveryChatController.php` - التحكم في الشات

### Services
- `app/Services/FirebaseService.php` - خدمة إرسال إشعارات Firebase

### Migrations
- `database/migrations/2025_11_29_create_order_messages_table.php`
- `database/migrations/2025_11_29_add_firebase_tokens_table.php`

### Config
- `config/services.php` - إعدادات Firebase

## سيناريوهات الاستخدام

### السيناريو 1: عميل يتواصل مع السائق
1. العميل يرسل رسالة: "أين أنت؟"
2. النظام يحفظ الرسالة في قاعدة البيانات
3. النظام يرسل إشعار Firebase للسائق
4. السائق يستلم الإشعار على هاتفه
5. السائق يفتح التطبيق ويرد: "سأصل خلال 5 دقائق"
6. العميل يستلم إشعار Firebase

### السيناريو 2: سائق يرسل صورة الموقع
1. السائق يرسل رسالة مع مرفق صورة
2. النظام يحفظ الصورة في storage
3. النظام يرسل إشعار للعميل
4. العميل يستلم الإشعار ويفتح الشات
5. العميل يشاهد الصورة

## الأمان والخصوصية
- ✅ التحقق من صحة البيانات باستخدام Validator
- ✅ حماية المرفقات (حد أقصى 10MB)
- ✅ أنواع ملفات محددة فقط (jpg, png, pdf, doc, docx)
- ✅ التحقق من وجود الطلب قبل إرسال الرسالة
- ⚠️ يُنصح بإضافة Authentication middleware للإنتاج

## التطوير المستقبلي
- [ ] إضافة دعم الرسائل الصوتية
- [ ] إضافة دعم الفيديو
- [ ] إضافة نظام مكالمات صوتية/مرئية
- [ ] إضافة ترجمة تلقائية للرسائل
- [ ] إضافة bot للردود التلقائية

## الدعم الفني
للمساعدة أو الإبلاغ عن مشاكل، يُرجى التواصل مع فريق التطوير.

## الترخيص
جميع الحقوق محفوظة © 2025
