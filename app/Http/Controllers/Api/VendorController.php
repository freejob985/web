<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::where('is_active', true)
            ->withCount('products');

        // Apply search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'products_count':
                $query->orderBy('products_count', $sortOrder);
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        // Apply pagination
        $perPage = $request->get('per_page', 12);
        $vendors = $query->paginate($perPage);

        // Transform vendors
        $vendors->getCollection()->transform(function($vendor) {
            // معالجة آمنة للأسماء مع دعم جميع الحالات
            $name = 'متجر'; // القيمة الافتراضية
            if (is_string($vendor->name) && !empty($vendor->name)) {
                $name = $vendor->name;
            } elseif (is_object($vendor->name) && isset($vendor->name->name)) {
                $name = $vendor->name->name;
            }
            
            $nameAr = 'متجر'; // القيمة الافتراضية بالعربية
            if (is_string($vendor->name_ar) && !empty($vendor->name_ar)) {
                $nameAr = $vendor->name_ar;
            } elseif (is_object($vendor->name_ar) && isset($vendor->name_ar->name)) {
                $nameAr = $vendor->name_ar->name;
            } elseif ($name !== 'متجر') {
                // إذا كان الاسم الإنجليزي موجود، استخدمه كبديل
                $nameAr = $name;
            }
            
            return [
                'id' => $vendor->id,
                'name' => $name,
                'name_ar' => $nameAr,
                'store_name' => $nameAr, // للتوافق مع التطبيق
                'description' => $vendor->description,
                'logo' => $vendor->logo_url,
                'cover_image' => $vendor->cover_image ? asset('storage/' . $vendor->cover_image) : null,
                'rating' => $vendor->rating ?? 4.5,
                'products_count' => is_numeric($vendor->products_count) ? (int) $vendor->products_count : 0,
                'orders_count' => $vendor->orders_count ?? 0,
                'is_featured' => $vendor->is_featured,
                'is_fresh' => $vendor->is_fresh ?? false,
                'category' => is_object($vendor->category) ? $vendor->category->name : ($vendor->category ?? 'عام'),
                'city' => is_object($vendor->city) ? $vendor->city->name : ($vendor->city ?? 'غير محدد'),
                'governorate' => is_object($vendor->governorate) ? $vendor->governorate->name : ($vendor->governorate ?? 'غير محدد'),
                'phone' => $vendor->phone,
                'email' => $vendor->email,
                'address' => $vendor->address,
                'created_at' => $vendor->created_at,
                'updated_at' => $vendor->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $vendors->items(),
            'meta' => [
                'total' => $vendors->total(),
                'per_page' => $vendors->perPage(),
                'current_page' => $vendors->currentPage(),
                'last_page' => $vendors->lastPage()
            ]
        ]);
    }

    public function show($id)
    {
        $vendor = Vendor::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)->limit(10);
            }])
            ->withCount('products')
            ->findOrFail($id);

        // معالجة آمنة لأسماء المتجر
        $vendorName = 'متجر';
        if (is_string($vendor->name) && !empty($vendor->name)) {
            $vendorName = $vendor->name;
        } elseif (is_object($vendor->name) && isset($vendor->name->name)) {
            $vendorName = $vendor->name->name;
        }
        
        $vendorNameAr = 'متجر';
        if (is_string($vendor->name_ar) && !empty($vendor->name_ar)) {
            $vendorNameAr = $vendor->name_ar;
        } elseif (is_object($vendor->name_ar) && isset($vendor->name_ar->name)) {
            $vendorNameAr = $vendor->name_ar->name;
        } elseif ($vendorName !== 'متجر') {
            $vendorNameAr = $vendorName;
        }

        $vendorData = [
            'id' => $vendor->id,
            'name' => $vendorName,
            'name_ar' => $vendorNameAr,
            'store_name' => $vendorNameAr, // للتوافق مع التطبيق
            'description' => $vendor->description,
            'logo' => $vendor->logo_url,
            'rating' => $vendor->rating ?? 4.5,
            'products_count' => $vendor->products_count,
            'orders_count' => $vendor->orders_count ?? 0,
            'reviews_count' => $vendor->reviews_count ?? 0,
            'is_featured' => $vendor->is_featured,
            'is_fresh' => $vendor->is_fresh ?? false,
            'category' => $vendor->category ?? 'عام',
            'city' => is_object($vendor->city) ? ['name_ar' => $vendor->city->name_ar ?? $vendor->city->name] : null,
            'governorate' => is_object($vendor->governorate) ? ['name_ar' => $vendor->governorate->name_ar ?? $vendor->governorate->name] : null,
            'phone' => $vendor->phone,
            'email' => $vendor->email,
            'address' => $vendor->address,
            'products' => $vendor->products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->getMainImage(),
                    'rating' => $product->rating ?? 4.5,
                    'is_fresh' => $product->is_fresh ?? false,
                    'is_featured' => $product->is_featured ?? false
                ];
            }),
            'created_at' => $vendor->created_at,
            'updated_at' => $vendor->updated_at
        ];

        return response()->json([
            'success' => true,
            'data' => $vendorData
        ]);
    }

    public function featured()
    {
        $vendors = Vendor::where('is_active', true)
            ->where('is_featured', true)
            ->withCount('products')
            ->orderBy('orders_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function($vendor) {
                // معالجة آمنة للأسماء
                $name = 'متجر';
                if (is_string($vendor->name) && !empty($vendor->name)) {
                    $name = $vendor->name;
                } elseif (is_object($vendor->name) && isset($vendor->name->name)) {
                    $name = $vendor->name->name;
                }
                
                $nameAr = 'متجر';
                if (is_string($vendor->name_ar) && !empty($vendor->name_ar)) {
                    $nameAr = $vendor->name_ar;
                } elseif (is_object($vendor->name_ar) && isset($vendor->name_ar->name)) {
                    $nameAr = $vendor->name_ar->name;
                } elseif ($name !== 'متجر') {
                    $nameAr = $name;
                }
                
                return [
                    'id' => $vendor->id,
                    'name' => $name,
                    'name_ar' => $nameAr,
                    'store_name' => $nameAr, // للتوافق مع التطبيق
                    'description' => $vendor->description,
                    'logo' => $vendor->logo_url,
                    'rating' => $vendor->rating ?? 4.5,
                    'products_count' => is_numeric($vendor->products_count) ? (int) $vendor->products_count : 0,
                    'orders_count' => $vendor->orders_count ?? 0,
                    'is_featured' => $vendor->is_featured,
                    'is_fresh' => $vendor->is_fresh ?? false,
                    'category' => is_object($vendor->category) ? $vendor->category->name : ($vendor->category ?? 'عام'),
                    'city' => is_object($vendor->city) ? $vendor->city->name : ($vendor->city ?? 'غير محدد'),
                    'governorate' => is_object($vendor->governorate) ? $vendor->governorate->name : ($vendor->governorate ?? 'غير محدد'),
                    'phone' => $vendor->phone,
                    'email' => $vendor->email,
                    'address' => $vendor->address
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $vendors
        ]);
    }

    public function products(Request $request, $id)
    {
        $vendor = Vendor::where('is_active', true)->findOrFail($id);

        \Log::info("🏪 [Vendor Products API] جلب منتجات المتجر #{$id} ({$vendor->name_ar})");
        
        $query = $vendor->products()
            ->with(['brand', 'category'])
            ->where('is_active', true);
            
        // عد جميع منتجات المتجر النشطة
        $totalActiveProducts = $vendor->products()->where('is_active', true)->count();
        \Log::info("📊 [Vendor Products API] إجمالي المنتجات النشطة: {$totalActiveProducts}");
            
        // تصفية حسب المخزون (اختياري - إظهار المنتجات حتى لو stock = 0 إلا إذا طلب المستخدم إخفاءها)
        if ($request->filled('in_stock_only') && $request->boolean('in_stock_only')) {
            $query->where('stock', '>', 0);
            \Log::info("🔍 [Vendor Products API] تصفية: المنتجات المتوفرة في المخزون فقط");
        }

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        if ($request->filled('fresh')) {
            $query->where('is_fresh', $request->boolean('fresh'));
        }

        if ($request->filled('on_sale')) {
            $query->whereNotNull('original_price')
                  ->whereColumn('original_price', '>', 'price');
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'sales':
                $query->orderBy('sales_count', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);
        
        \Log::info("✅ [Vendor Products API] تم جلب {$products->count()} منتج من أصل {$products->total()}");

        // Transform products
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'original_price' => $product->original_price ? (float) $product->original_price : null,
                'discount_percentage' => $product->discount_percentage,
                'is_on_sale' => $product->is_on_sale,
                'is_featured' => $product->is_featured,
                'is_fresh' => $product->is_fresh,
                'stock' => $product->stock,
                'rating' => $product->rating,
                'sales_count' => $product->sales_count,
                'images' => $product->getImages(),
                'main_image' => $product->getMainImage(),
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name
                ] : null,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name
                ] : null,
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString()
            ];
        });

        // معالجة آمنة لأسماء المتجر
        $vendorName = 'متجر';
        if (is_string($vendor->name) && !empty($vendor->name)) {
            $vendorName = $vendor->name;
        } elseif (is_object($vendor->name) && isset($vendor->name->name)) {
            $vendorName = $vendor->name->name;
        }
        
        $vendorNameAr = 'متجر';
        if (is_string($vendor->name_ar) && !empty($vendor->name_ar)) {
            $vendorNameAr = $vendor->name_ar;
        } elseif (is_object($vendor->name_ar) && isset($vendor->name_ar->name)) {
            $vendorNameAr = $vendor->name_ar->name;
        } elseif ($vendorName !== 'متجر') {
            $vendorNameAr = $vendorName;
        }
        
        return response()->json([
            'success' => true,
            'data' => $products->items(), // للتوافق مع api_service.dart
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendorName,
                'name_ar' => $vendorNameAr,
                'store_name' => $vendorNameAr, // للتوافق مع التطبيق
                'description' => $vendor->description,
                'logo' => $vendor->logo_url,
                'rating' => $vendor->rating ?? 4.5,
                'is_featured' => $vendor->is_featured,
                'is_fresh' => $vendor->is_fresh ?? false,
                'category' => is_object($vendor->category) ? $vendor->category->name : ($vendor->category ?? 'عام'),
                'city' => is_object($vendor->city) ? $vendor->city->name : ($vendor->city ?? 'غير محدد'),
                'governorate' => is_object($vendor->governorate) ? $vendor->governorate->name : ($vendor->governorate ?? 'غير محدد'),
                'phone' => $vendor->phone,
                'email' => $vendor->email,
                'address' => $vendor->address
            ],
            'products' => $products->items(),
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage()
            ],
            'filters' => [
                'categories' => $vendor->products()
                    ->active()
                    ->with('category')
                    ->get()
                    ->pluck('category')
                    ->filter()
                    ->unique('id')
                    ->values()
                    ->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name_ar ?? $category->name_en ?? 'غير محدد'
                        ];
                    }),
                'brands' => $vendor->products()
                    ->active()
                    ->with('brand')
                    ->get()
                    ->pluck('brand')
                    ->filter()
                    ->unique('id')
                    ->values()
                    ->map(function ($brand) {
                        return [
                            'id' => $brand->id,
                            'name' => $brand->name ?? 'غير محدد'
                        ];
                    }),
                'price_range' => [
                    'min' => $vendor->products()->active()->min('price'),
                    'max' => $vendor->products()->active()->max('price')
                ]
            ]
        ]);
    }
}
