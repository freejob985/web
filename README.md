# Engeb E-commerce Platform

منصة التسوق الإلكتروني الرائدة في الكويت، نقدم لك أفضل المنتجات الغذائية والاستهلاكية من موردين موثوقين.

## 🚀 المميزات

### Backend (Laravel)
- ✅ **إدارة الأقسام**: الأقسام الرئيسية والفرعية
- ✅ **إدارة المواقع**: المحافظات والمدن الكويتية
- ✅ **إدارة المنتجات**: مع ربط الأقسام والمواقع
- ✅ **إدارة الموردين**: مع حقول المحافظة والمدينة
- ✅ **API متكامل**: للفرونت إند
- ✅ **واجهة إدارة**: متطورة ومنظمة

### Frontend (React + TypeScript)
- ✅ **صفحة الأقسام**: عرض ديناميكي من API
- ✅ **Footer محدث**: مع الأقسام الرئيسية وأقسام السوبر ماركت
- ✅ **إعدادات API**: منظمة ومرنة
- ✅ **تصميم متجاوب**: يعمل على جميع الأجهزة

## 📁 هيكل المشروع

```
engeb/
├── app/                          # Laravel Backend
│   ├── Http/Controllers/         # Controllers
│   │   ├── Admin/               # Admin Controllers
│   │   └── Api/                 # API Controllers
│   └── Models/                   # Eloquent Models
├── database/
│   ├── migrations/               # Database Migrations
│   └── seeders/                 # Data Seeders
├── resources/views/admin/        # Admin Blade Views
├── routes/
│   ├── admin.php                # Admin Routes
│   └── api.php                  # API Routes
├── Front/                       # React Frontend
│   ├── src/
│   │   ├── components/          # React Components
│   │   ├── pages/              # Page Components
│   │   ├── config/             # API Configuration
│   │   └── lib/                # API Library
│   └── package.json
└── README.md
```

## 🛠️ التثبيت والتشغيل

### 1. Backend (Laravel)

```bash
# تثبيت المتطلبات
composer install

# إعداد قاعدة البيانات
cp .env.example .env
php artisan key:generate

# تشغيل المايجريشن والبيانات
php artisan migrate
php artisan db:seed --class=KuwaitDataSeeder
php artisan db:seed --class=CategorySeeder

# تشغيل الخادم
php artisan serve
```

### 2. Frontend (React)

```bash
cd Front

# تثبيت المتطلبات
npm install

# تشغيل الخادم
npm run dev
```

### 3. تشغيل سريع

```bash
# تشغيل كل شيء
start-all.bat

# أو تشغيل منفصل
start-backend.bat
cd Front && npm run dev
```

## 🌐 الروابط

### Backend
- **API Base**: http://localhost:8000/api/v1
- **Admin Panel**: http://localhost:8000/admin
- **API Docs**: http://localhost:8000/api/v1/categories

### Frontend
- **Main App**: http://localhost:5173
- **Categories**: http://localhost:5173/categories
- **Products**: http://localhost:5173/products

## 📊 البيانات

### الأقسام
- **8 أقسام رئيسية** مع 32 قسم فرعي
- **6 محافظات** كويتية
- **45+ مدينة** موزعة على المحافظات

### API Endpoints
- `GET /api/v1/categories` - الأقسام الرئيسية
- `GET /api/v1/subcategories` - الأقسام الفرعية
- `GET /api/v1/governorates` - المحافظات
- `GET /api/v1/cities` - المدن
- `GET /api/v1/products` - المنتجات

## 🔧 الإعدادات

### Environment Variables

#### Backend (.env)
```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=engeb
```

#### Frontend (src/config/api.ts)
```typescript
export const API_CONFIG = {
  BASE_URL: 'http://localhost:8000',
  API_PREFIX: 'api/v1',
  ADMIN_URL: 'http://localhost:8000/admin',
  FRONTEND_URL: 'http://localhost:5173',
};
```

## 📱 المميزات الجديدة

### 1. إدارة الأقسام
- ✅ إضافة/تعديل/حذف الأقسام الرئيسية
- ✅ إضافة/تعديل/حذف الأقسام الفرعية
- ✅ ربط الأقسام الفرعية بالأقسام الرئيسية
- ✅ إدارة الصور والأيقونات

### 2. إدارة المواقع
- ✅ إضافة/تعديل/حذف المحافظات
- ✅ إضافة/تعديل/حذف المدن
- ✅ ربط المدن بالمحافظات
- ✅ بيانات الكويت الكاملة

### 3. إدارة المنتجات
- ✅ ربط المنتجات بالأقسام
- ✅ ربط المنتجات بالمواقع الجغرافية
- ✅ Select dropdowns ديناميكية
- ✅ فلترة حسب القسم والموقع

### 4. إدارة الموردين
- ✅ حقول المحافظة والمدينة
- ✅ Select dropdowns مرتبطة
- ✅ JavaScript تفاعلي
- ✅ ربط الموردين بالمواقع

### 5. Frontend
- ✅ Footer محدث مع الأقسام
- ✅ ربط API ديناميكي
- ✅ إعدادات منظمة
- ✅ مكونات قابلة لإعادة الاستخدام

## 🎯 الخطوات التالية

1. **إضافة المزيد من الأقسام**
2. **تحسين واجهة الإدارة**
3. **إضافة المزيد من المميزات**
4. **تحسين الأداء**
5. **إضافة الاختبارات**

## 📞 الدعم

للمساعدة والدعم، يرجى التواصل مع فريق التطوير.

---

**© 2024 Engeb. جميع الحقوق محفوظة.**
