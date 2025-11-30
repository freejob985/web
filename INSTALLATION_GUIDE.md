# دليل التثبيت خطوة بخطوة - نظام إشعارات Firebase للشات

## الخطوة 1: تثبيت مكتبة Firebase PHP

```bash
composer require kreait/firebase-php
```

**الوقت المتوقع:** 2-3 دقائق

---

## الخطوة 2: إنشاء مشروع Firebase

1. افتح [Firebase Console](https://console.firebase.google.com/)
2. اضغط على "Add Project" أو "إضافة مشروع"
3. أدخل اسم المشروع (مثلاً: "salla-delivery")
4. اضغط "Continue" → اختر التفضيلات → اضغط "Create Project"

**الوقت المتوقع:** 2 دقيقة

---

## الخطوة 3: الحصول على ملف Credentials

1. من لوحة تحكم Firebase، اذهب إلى:
   - ⚙️ Settings (الإعدادات)
   - Project Settings (إعدادات المشروع)
   - Service Accounts (حسابات الخدمة)

2. اضغط على "Generate New Private Key"

3. سيتم تنزيل ملف JSON

4. انقل الملف إلى:
   ```
   storage/app/firebase/credentials.json
   ```

5. تأكد من الأذونات:
   ```bash
   chmod 600 storage/app/firebase/credentials.json
   ```

**الوقت المتوقع:** 3 دقائق

---

## الخطوة 4: تحديث ملف .env

افتح ملف `.env` وأضف:

```env
FIREBASE_CREDENTIALS=storage/app/firebase/credentials.json
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
```

**ملاحظة:** استبدل `your-project-id` بمعرف مشروعك من Firebase Console

**الوقت المتوقع:** 1 دقيقة

---

## الخطوة 5: تشغيل الـ Migrations

```bash
php artisan migrate
```

هذا سينشئ:
- ✅ جدول `order_messages`
- ✅ جدول `drivers` (إذا لم يكن موجوداً)
- ✅ إضافة `fcm_token` للمستخدمين
- ✅ إضافة `driver_id` للطلبات

**الوقت المتوقع:** 10 ثانية

---

## الخطوة 6: إنشاء سائق تجريبي (اختياري للتجربة)

افتح Tinker:
```bash
php artisan tinker
```

ثم أدخل:
```php
$driver = new App\Models\Driver();
$driver->name = 'أحمد السائق';
$driver->phone = '0771234567';
$driver->email = 'driver@example.com';
$driver->license_number = 'LIC-12345';
$driver->vehicle_type = 'سيارة';
$driver->vehicle_number = 'ABC-123';
$driver->status = 'active';
$driver->save();

echo "Driver ID: " . $driver->id;
```

**احفظ هذا ID** - ستحتاجه للاختبار

**الوقت المتوقع:** 2 دقيقة

---

## الخطوة 7: تعيين سائق لطلب

```php
// في Tinker
$order = App\Models\Order::first();
$order->driver_id = 1; // استخدم ID السائق من الخطوة السابقة
$order->save();
```

**الوقت المتوقع:** 1 دقيقة

---

## الخطوة 8: استيراد Postman Collection

1. افتح Postman
2. اضغط "Import"
3. اختر ملف `postman_collection_firebase_chat.json`
4. غيّر المتغيرات:
   - `base_url`: رابط API الخاص بك
   - `order_id`: معرف طلب موجود

**الوقت المتوقع:** 2 دقيقة

---

## الخطوة 9: الاختبار الأولي (بدون Firebase)

### 1. إرسال رسالة من مستخدم:

```bash
curl -X POST https://YOUR_URL/api/v1/salla-delivery/chat/send/1 \
-H "Content-Type: application/json" \
-d '{
  "message": "مرحباً، هذه رسالة تجريبية",
  "sender_type": "user",
  "sender_id": 1
}'
```

### 2. الحصول على الرسائل:

```bash
curl https://YOUR_URL/api/v1/salla-delivery/chat/messages/1
```

**الوقت المتوقع:** 3 دقائق

---

## الخطوة 10: تحديث Firebase Tokens

### للمستخدم:
```bash
curl -X POST https://YOUR_URL/api/v1/salla-delivery/chat/update-fcm-token \
-H "Content-Type: application/json" \
-d '{
  "user_type": "user",
  "user_id": 1,
  "fcm_token": "YOUR_FIREBASE_TOKEN_HERE"
}'
```

### للسائق:
```bash
curl -X POST https://YOUR_URL/api/v1/salla-delivery/chat/update-fcm-token \
-H "Content-Type: application/json" \
-d '{
  "user_type": "driver",
  "user_id": 1,
  "fcm_token": "YOUR_DRIVER_FIREBASE_TOKEN_HERE"
}'
```

**الوقت المتوقع:** 2 دقيقة

---

## الخطوة 11: إعداد Firebase في التطبيق (React Native)

### أ. تثبيت المكتبات:

```bash
npm install @react-native-firebase/app @react-native-firebase/messaging
```

### ب. إعداد Firebase للأندرويد:

1. انزل ملف `google-services.json` من Firebase Console
2. انقله إلى: `android/app/google-services.json`

### ج. إعداد Firebase لـ iOS:

1. انزل ملف `GoogleService-Info.plist` من Firebase Console
2. انقله إلى مجلد iOS في Xcode

### د. استخدم الكود من ملف:
```
frontend_firebase_integration.js
```

**الوقت المتوقع:** 15 دقيقة

---

## الخطوة 12: اختبار الإشعارات الكاملة

### السيناريو الكامل:

1. **افتح تطبيق المستخدم**:
   - سجل Firebase token للمستخدم

2. **افتح تطبيق السائق**:
   - سجل Firebase token للسائق

3. **من تطبيق المستخدم**:
   - أرسل رسالة للسائق
   - ✅ يجب أن يستلم السائق إشعار

4. **من تطبيق السائق**:
   - أرسل رسالة للمستخدم
   - ✅ يجب أن يستلم المستخدم إشعار

**الوقت المتوقع:** 5 دقائق

---

## استكشاف الأخطاء

### خطأ: "Firebase credentials file not found"
**الحل:**
```bash
# تحقق من وجود الملف
ls -la storage/app/firebase/credentials.json

# إذا لم يكن موجود، أنشئ المجلد
mkdir -p storage/app/firebase
```

---

### خطأ: "Table 'drivers' doesn't exist"
**الحل:**
```bash
# أعد تشغيل migrations
php artisan migrate:fresh
# أو
php artisan migrate --force
```

---

### خطأ: "FCM token is null"
**الحل:**
- تأكد من تسجيل Firebase token قبل إرسال الرسائل
- استخدم endpoint `/update-fcm-token` أولاً

---

### لا تصل الإشعارات
**الحل:**
1. تحقق من logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. تأكد من صحة Firebase token

3. راجع إعدادات Firebase Console

4. تحقق من أذونات التطبيق للإشعارات

---

## ملاحظات أمنية مهمة

### للإنتاج (Production):

1. **أضف Authentication Middleware**:
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       // chat routes
   });
   ```

2. **تشفير اتصال Firebase**:
   - استخدم HTTPS فقط
   - حافظ على سرية ملف credentials.json

3. **Rate Limiting**:
   ```php
   Route::middleware('throttle:60,1')->group(function () {
       // chat routes
   });
   ```

4. **Validation معززة**:
   - تحقق من ملكية المستخدم للطلب
   - تحقق من صلاحيات السائق

---

## الدعم والمساعدة

### الملفات المرجعية:
- 📄 `FIREBASE_CHAT_NOTIFICATIONS.md` - التوثيق الشامل
- 📄 `frontend_firebase_integration.js` - أمثلة Frontend
- 📄 `postman_collection_firebase_chat.json` - مجموعة Postman

### السجلات (Logs):
```bash
# مراقبة السجلات مباشرة
tail -f storage/logs/laravel.log | grep Firebase
```

---

## الخطوات التالية (اختياري)

- [ ] إضافة Real-time messaging باستخدام WebSockets
- [ ] إضافة نظام الملفات الصوتية
- [ ] إضافة نظام المكالمات
- [ ] إضافة ترجمة تلقائية
- [ ] إضافة إحصائيات الشات

---

## ملخص سريع

```bash
# 1. تثبيت Firebase
composer require kreait/firebase-php

# 2. وضع ملف credentials في
storage/app/firebase/credentials.json

# 3. تحديث .env
# أضف FIREBASE_CREDENTIALS و FIREBASE_DATABASE_URL

# 4. تشغيل migrations
php artisan migrate

# 5. اختبار API
# استخدم Postman Collection

# 6. إعداد Frontend
# استخدم frontend_firebase_integration.js
```

---

**الوقت الإجمالي للإعداد الكامل:** 30-45 دقيقة

✅ **تم الانتهاء من الإعداد!**
