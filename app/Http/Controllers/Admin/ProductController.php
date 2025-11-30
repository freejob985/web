<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Governorate;
use App\Models\City;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20); // Default: 20 items per page
        
        // Validate per_page is within reasonable limits
        if (!in_array($perPage, [10, 20, 50, 100, 200])) {
            $perPage = 20;
        }
        
        $products = Product::with('vendor')->latest()->paginate($perPage);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $vendors = Vendor::orderBy('name')->get();
        $categories = Category::active()->ordered()->get();
        $subcategories = Subcategory::active()->ordered()->get();
        $governorates = Governorate::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();
        return view('admin.products.create', compact('vendors', 'categories', 'subcategories', 'governorates', 'cities', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'image' => 'nullable|string',
            'images' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'origin' => 'required|string|max:255',
            'weight' => 'nullable|numeric',
            'unit' => 'required|string|max:255',
            'is_fresh' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'nullable|in:pending,approved,rejected',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'vendor_id' => 'required|exists:vendors,id',
            'nutritional_info' => 'nullable|string',
            'expiry_date' => 'nullable|date'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'name.string' => 'اسم المنتج يجب أن يكون نص',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من أو يساوي 0',
            'original_price.numeric' => 'السعر الأصلي يجب أن يكون رقم',
            'original_price.min' => 'السعر الأصلي يجب أن يكون أكبر من أو يساوي 0',
            'stock.required' => 'المخزون مطلوب',
            'stock.integer' => 'المخزون يجب أن يكون رقم صحيح',
            'stock.min' => 'المخزون يجب أن يكون أكبر من أو يساوي 0',
            'sku.required' => 'كود المنتج مطلوب',
            'sku.string' => 'كود المنتج يجب أن يكون نص',
            'sku.unique' => 'كود المنتج موجود مسبقاً',
            'barcode.string' => 'الباركود يجب أن يكون نص',
            'barcode.unique' => 'الباركود موجود مسبقاً',
            'category_id.exists' => 'القسم الرئيسي المحدد غير صحيح',
            'subcategory_id.exists' => 'القسم الفرعي المحدد غير صحيح',
            'governorate_id.exists' => 'المحافظة المحددة غير صحيحة',
            'city_id.exists' => 'المدينة المحددة غير صحيحة',
            'brand_id.exists' => 'الماركة المحددة غير صحيحة',
            'origin.required' => 'المنشأ مطلوب',
            'origin.string' => 'المنشأ يجب أن يكون نص',
            'origin.max' => 'المنشأ لا يجب أن يتجاوز 255 حرف',
            'weight.numeric' => 'الوزن يجب أن يكون رقم',
            'unit.required' => 'الوحدة مطلوبة',
            'unit.string' => 'الوحدة يجب أن تكون نص',
            'unit.max' => 'الوحدة لا يجب أن تتجاوز 255 حرف',
            'is_fresh.boolean' => 'حالة الطازج يجب أن تكون صحيح أو خطأ',
            'is_featured.boolean' => 'حالة المميز يجب أن تكون صحيح أو خطأ',
            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيح أو خطأ',
            'status.in' => 'حالة المنتج يجب أن تكون: pending, approved, أو rejected',
            'rating.numeric' => 'التقييم يجب أن يكون رقم',
            'rating.min' => 'التقييم يجب أن يكون أكبر من أو يساوي 0',
            'rating.max' => 'التقييم يجب أن يكون أقل من أو يساوي 5',
            'reviews_count.integer' => 'عدد المراجعات يجب أن يكون رقم صحيح',
            'reviews_count.min' => 'عدد المراجعات يجب أن يكون أكبر من أو يساوي 0',
            'sales_count.integer' => 'عدد المبيعات يجب أن يكون رقم صحيح',
            'sales_count.min' => 'عدد المبيعات يجب أن يكون أكبر من أو يساوي 0',
            'vendor_id.required' => 'المورد مطلوب',
            'vendor_id.exists' => 'المورد المحدد غير صحيح',
            'nutritional_info.string' => 'المعلومات الغذائية يجب أن تكون نص',
            'expiry_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخ صحيح'
        ]);

        // Image is handled by Dropzone and passed as a string path
        if ($request->filled('image')) {
            $validated['image'] = $request->input('image');
        }

        $validated['is_fresh'] = $request->boolean('is_fresh');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $vendors = Vendor::orderBy('name')->get();
        $categories = Category::active()->ordered()->get();
        $subcategories = Subcategory::active()->ordered()->get();
        $governorates = Governorate::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();
        return view('admin.products.edit', compact('product', 'vendors', 'categories', 'subcategories', 'governorates', 'cities', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'image' => 'nullable|string',
            'images' => 'nullable|string',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'brand_id' => 'nullable|exists:brands,id',
            'origin' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'unit' => 'required|string',
            'is_fresh' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'nullable|numeric',
            'reviews_count' => 'nullable|integer',
            'sales_count' => 'nullable|integer',
            'vendor_id' => 'required|exists:vendors,id',
            'nutritional_info' => 'nullable|string',
            'expiry_date' => 'nullable|date'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'name.string' => 'اسم المنتج يجب أن يكون نص',
            'name.max' => 'اسم المنتج لا يجب أن يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من أو يساوي 0',
            'original_price.numeric' => 'السعر الأصلي يجب أن يكون رقم',
            'original_price.min' => 'السعر الأصلي يجب أن يكون أكبر من أو يساوي 0',
            'stock.required' => 'المخزون مطلوب',
            'stock.integer' => 'المخزون يجب أن يكون رقم صحيح',
            'stock.min' => 'المخزون يجب أن يكون أكبر من أو يساوي 0',
            'sku.required' => 'كود المنتج مطلوب',
            'sku.string' => 'كود المنتج يجب أن يكون نص',
            'sku.unique' => 'كود المنتج موجود مسبقاً',
            'barcode.string' => 'الباركود يجب أن يكون نص',
            'barcode.unique' => 'الباركود موجود مسبقاً',
            'category_id.exists' => 'القسم الرئيسي المحدد غير صحيح',
            'subcategory_id.exists' => 'القسم الفرعي المحدد غير صحيح',
            'governorate_id.exists' => 'المحافظة المحددة غير صحيحة',
            'city_id.exists' => 'المدينة المحددة غير صحيحة',
            'brand_id.exists' => 'الماركة المحددة غير صحيحة',
            'origin.required' => 'المنشأ مطلوب',
            'origin.string' => 'المنشأ يجب أن يكون نص',
            'origin.max' => 'المنشأ لا يجب أن يتجاوز 255 حرف',
            'weight.numeric' => 'الوزن يجب أن يكون رقم',
            'unit.required' => 'الوحدة مطلوبة',
            'unit.string' => 'الوحدة يجب أن تكون نص',
            'unit.max' => 'الوحدة لا يجب أن تتجاوز 255 حرف',
            'is_fresh.boolean' => 'حالة الطازج يجب أن تكون صحيح أو خطأ',
            'is_featured.boolean' => 'حالة المميز يجب أن تكون صحيح أو خطأ',
            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيح أو خطأ',
            'status.in' => 'حالة المنتج يجب أن تكون: pending, approved, أو rejected',
            'rating.numeric' => 'التقييم يجب أن يكون رقم',
            'rating.min' => 'التقييم يجب أن يكون أكبر من أو يساوي 0',
            'rating.max' => 'التقييم يجب أن يكون أقل من أو يساوي 5',
            'reviews_count.integer' => 'عدد المراجعات يجب أن يكون رقم صحيح',
            'reviews_count.min' => 'عدد المراجعات يجب أن يكون أكبر من أو يساوي 0',
            'sales_count.integer' => 'عدد المبيعات يجب أن يكون رقم صحيح',
            'sales_count.min' => 'عدد المبيعات يجب أن يكون أكبر من أو يساوي 0',
            'vendor_id.required' => 'المورد مطلوب',
            'vendor_id.exists' => 'المورد المحدد غير صحيح',
            'nutritional_info.string' => 'المعلومات الغذائية يجب أن تكون نص',
            'expiry_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخ صحيح'
        ]);

        // Image is handled by Dropzone and passed as a string path
        if ($request->filled('image')) {
            $validated['image'] = $request->input('image');
        }

        $validated['is_fresh'] = $request->boolean('is_fresh');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('products', 'public');
                
                // التأكد من أن الملف تم رفعه بنجاح
                if (file_exists(storage_path('app/public/' . $path))) {
                    return response()->json([
                        'success' => true,
                        'path' => $path,
                        'url' => asset('storage/' . $path)
                    ]);
                } else {
                    return response()->json(['error' => 'فشل في رفع الملف'], 500);
                }
            }

            return response()->json(['error' => 'لم يتم رفع أي ملف'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطأ في رفع الملف: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    public function showImport()
    {
        return view('admin.products.import');
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('templates/products_import_template.csv');
        
        // Check if template exists
        if (!file_exists($templatePath)) {
            // Create template directory if it doesn't exist
            if (!file_exists(public_path('templates'))) {
                mkdir(public_path('templates'), 0755, true);
            }
            
            // Create the template file with UTF-8 BOM
            $content = "\xEF\xBB\xBF"; // UTF-8 BOM
            $content .= "name,description,price,original_price,stock,sku,barcode,image,category_id,subcategory_id,brand_id,origin,weight,unit,is_fresh,is_featured,is_active,status,rating,reviews_count,sales_count,vendor_id,governorate_id,city_id,nutritional_info,expiry_date\r\n";
            $content .= "\"تفاح أحمر\",\"تفاح أحمر طازج من المزارع المحلية\",2.500,3.000,100,APPLE-001,8901234567890,,1,1,1,الكويت,1.0,كيلو,1,0,1,approved,4.5,10,25,1,1,1,\"سعرات حرارية: 52\",2024-12-31\r\n";
            $content .= "برتقال,\"برتقال طازج غني بفيتامين سي\",1.750,2.000,150,ORANGE-001,8901234567891,,فواكه,حمضيات,\"مزارع الخليج\",الكويت,2.0,كيلو,true,true,yes,approved,4.8,15,40,1,1,1,\"فيتامين سي: 53 ملغ\",2024-11-30\r\n";
            $content .= "\"حليب كامل الدسم\",\"حليب طازج كامل الدسم\",1.200,,200,MILK-001,8901234567892,,2,\"ألبان طازجة\",2,الكويت,1.0,لتر,1,0,1,approved,4.2,8,60,1,1,1,\"بروتين: 3.4g\",2024-10-15\r\n";
            
            file_put_contents($templatePath, $content);
        }
        
        // Read and return the file
        $content = file_get_contents($templatePath);
        
        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_import_template.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function import(Request $request)
    {
        // زيادة وقت التنفيذ والذاكرة للملفات الكبيرة
        ini_set('max_execution_time', 600); // 10 دقائق
        ini_set('memory_limit', '512M'); // 512 ميجابايت
        
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240' // 10MB max
        ], [
            'file.required' => 'الملف مطلوب',
            'file.file' => 'يجب أن يكون ملف',
            'file.mimes' => 'يجب أن يكون ملف CSV أو Excel',
            'file.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت'
        ]);

        try {
            // Start tracking execution time
            $startTime = microtime(true);
            
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                return $this->importCsv($file, $startTime);
            } else {
                return redirect()->back()->with('error', 'نوع الملف غير مدعوم حالياً. يرجى استخدام ملف CSV');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Find or create a category by ID or name
     * @param string|int $value - Category ID or name
     * @return int|null - Category ID
     */
    private function findOrCreateCategory($value)
    {
        if (empty($value)) {
            return null;
        }

        // If numeric, use as ID
        if (is_numeric($value)) {
            $category = Category::find(intval($value));
            return $category ? $category->id : null;
        }

        // If text, find or create by name
        $category = Category::where('name_ar', $value)->first();
        
        if (!$category) {
            // Create new category
            $category = Category::create([
                'name_ar' => $value,
                'name_en' => '',
                'is_active' => true,
                'sort_order' => 0
            ]);
        }

        return $category->id;
    }

    /**
     * Find or create a subcategory by ID or name
     * @param string|int $value - Subcategory ID or name
     * @param int|null $categoryId - Parent category ID
     * @return int|null - Subcategory ID
     */
    private function findOrCreateSubcategory($value, $categoryId = null)
    {
        if (empty($value)) {
            return null;
        }

        // If numeric, use as ID
        if (is_numeric($value)) {
            $subcategory = Subcategory::find(intval($value));
            return $subcategory ? $subcategory->id : null;
        }

        // If text, find or create by name
        $query = Subcategory::where('name_ar', $value);
        
        // If category_id is provided, scope to that category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $subcategory = $query->first();
        
        if (!$subcategory) {
            // Create new subcategory
            $subcategory = Subcategory::create([
                'name_ar' => $value,
                'name_en' => '',
                'category_id' => $categoryId,
                'is_active' => true,
                'sort_order' => 0
            ]);
        }

        return $subcategory->id;
    }

    /**
     * Find or create a brand by ID or name
     * @param string|int $value - Brand ID or name
     * @return int|null - Brand ID
     */
    private function findOrCreateBrand($value)
    {
        if (empty($value)) {
            return null;
        }

        // If numeric, use as ID
        if (is_numeric($value)) {
            $brand = Brand::find(intval($value));
            return $brand ? $brand->id : null;
        }

        // If text, find or create by name
        $brand = Brand::where('name', $value)->first();
        
        if (!$brand) {
            // Create new brand
            $brand = Brand::create([
                'name' => $value,
                'is_active' => true,
                'sort_order' => 0
            ]);
        }

        return $brand->id;
    }

    /**
     * Generate a unique random barcode
     * @return string - Unique 13-digit barcode
     */
    private function generateUniqueBarcode()
    {
        do {
            // Generate a random 13-digit barcode (EAN-13 format)
            $barcode = '';
            for ($i = 0; $i < 13; $i++) {
                $barcode .= rand(0, 9);
            }
        } while (Product::where('barcode', $barcode)->exists());
        
        return $barcode;
    }

    private function importCsv($file, $startTime)
    {
        // Set locale for UTF-8 support
        setlocale(LC_ALL, 'en_US.UTF-8');
        
        $handle = fopen($file->getPathname(), 'r');
        
        // Read header row
        $header = fgetcsv($handle);
        
        // Convert header to UTF-8 if needed
        if ($header) {
            $header = array_map(function($item) {
                return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }, $header);
        }
        
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            
            // Convert data to UTF-8 if needed
            $data = array_map(function($item) {
                if ($item === null || $item === '') return $item;
                return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }, $data);
            
            try {
                // Process category, subcategory, and brand (can be ID or name)
                // Note: Column indices updated to match new CSV structure with barcode at position 6
                $categoryId = $this->findOrCreateCategory($data[8] ?? null);
                $subcategoryId = $this->findOrCreateSubcategory($data[9] ?? null, $categoryId);
                $brandId = $this->findOrCreateBrand($data[10] ?? null);
                
                // Helper function to parse boolean values
                $parseBoolean = function($value) {
                    if (empty($value)) return false;
                    $value = strtolower(trim($value));
                    return in_array($value, ['1', 'true', 'yes']);
                };
                
                // Map CSV data to product fields with default values
                // Column order: name, description, price, original_price, stock, sku, barcode, image, category_id, subcategory_id, brand_id, origin, weight, unit, is_fresh, is_featured, is_active, status, rating, reviews_count, sales_count, vendor_id, governorate_id, city_id, nutritional_info, expiry_date
                $productData = [
                    'name' => $data[0] ?? '',
                    'description' => $data[1] ?? '',
                    'price' => floatval($data[2] ?? 0),
                    'original_price' => !empty($data[3]) ? floatval($data[3]) : null,
                    'stock' => !empty($data[4]) ? intval($data[4]) : 0,  // Default: 0
                    'sku' => $data[5] ?? '',
                    'barcode' => !empty($data[6]) ? $data[6] : null,
                    'image' => $data[7] ?? '',
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcategoryId ?? null,  // Default: null
                    'brand_id' => $brandId ?? null,  // Default: null
                    'origin' => !empty($data[11]) ? $data[11] : 'الكويت',  // Default: الكويت
                    'weight' => !empty($data[12]) ? floatval($data[12]) : null,
                    'unit' => !empty($data[13]) ? $data[13] : 'كيلو',  // Default: كيلو
                    'is_fresh' => $parseBoolean($data[14] ?? '0'),  // Default: false
                    'is_featured' => $parseBoolean($data[15] ?? '0'),  // Default: false
                    'is_active' => $parseBoolean($data[16] ?? '1'),  // Default: true
                    'status' => in_array($data[17] ?? 'pending', ['pending', 'approved', 'rejected']) ? ($data[17] ?? 'pending') : 'pending',  // Default: pending
                    'rating' => !empty($data[18]) ? floatval($data[18]) : 0.0,  // Default: 0.0
                    'reviews_count' => !empty($data[19]) ? intval($data[19]) : 0,  // Default: 0
                    'sales_count' => !empty($data[20]) ? intval($data[20]) : 0,  // Default: 0
                    'vendor_id' => !empty($data[21]) ? intval($data[21]) : 1,  // Default: 1
                    'governorate_id' => !empty($data[22]) ? intval($data[22]) : null,  // Default: null
                    'city_id' => !empty($data[23]) ? intval($data[23]) : null,  // Default: null
                    'nutritional_info' => $data[24] ?? null,  // Default: null
                    'expiry_date' => !empty($data[25]) ? $data[25] : null  // Default: null
                ];

                // Validate required fields
                if (empty($productData['name'])) {
                    $errors[] = "الصف $row: اسم المنتج مطلوب";
                    continue;
                }

                if (empty($productData['sku'])) {
                    $errors[] = "الصف $row: كود المنتج مطلوب";
                    continue;
                }

                // معالجة مشكلة الأرقام العلمية من Excel (مثل 3.57467E+12)
                // تحويل SKU إلى رقم صحيح إذا كان بصيغة علمية
                if (strpos($productData['sku'], 'E') !== false || strpos($productData['sku'], 'e') !== false) {
                    // تحويل من صيغة علمية إلى رقم عادي
                    $productData['sku'] = sprintf('%.0f', floatval($productData['sku']));
                }

                if ($productData['price'] <= 0) {
                    $errors[] = "الصف $row: السعر يجب أن يكون أكبر من صفر";
                    continue;
                }

                // تحقق من وجود منتج بنفس الاسم - إذا كان موجود، قم بتخطيه
                if (Product::where('name', $productData['name'])->exists()) {
                    $skipped++;
                    continue;
                }

                // إذا كان SKU موجود مسبقاً، قم بتعديله بإضافة suffix فريد
                $originalSku = $productData['sku'];
                $counter = 1;
                while (Product::where('sku', $productData['sku'])->exists()) {
                    $productData['sku'] = $originalSku . '-' . $counter;
                    $counter++;
                    
                    // حماية من حلقة لا نهائية
                    if ($counter > 1000) {
                        $errors[] = "الصف $row: فشل في توليد SKU فريد بعد 1000 محاولة";
                        continue 2; // الخروج من while والـ while الخارجي
                    }
                }

                // إذا كان الباركود مكرر، قم بتوليد باركود عشوائي جديد
                if (!empty($productData['barcode']) && Product::where('barcode', $productData['barcode'])->exists()) {
                    $productData['barcode'] = $this->generateUniqueBarcode();
                }

                // Check if vendor exists
                if (!Vendor::find($productData['vendor_id'])) {
                    $errors[] = "الصف $row: المورد المحدد غير موجود";
                    continue;
                }

                // Handle governorate and city by ID
                if (!empty($productData['governorate_id'])) {
                    $governorate = Governorate::find($productData['governorate_id']);
                    if (!$governorate) {
                        $errors[] = "الصف $row: المحافظة المحددة غير موجودة";
                        continue;
                    }
                }

                if (!empty($productData['city_id'])) {
                    $city = City::find($productData['city_id']);
                    if (!$city) {
                        $errors[] = "الصف $row: المدينة المحددة غير موجودة";
                        continue;
                    }
                }

                // Create product
                Product::create($productData);
                $imported++;

            } catch (\Exception $e) {
                $errors[] = "الصف $row: " . $e->getMessage();
            }
        }

        fclose($handle);

        // Calculate execution time
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);

        $message = "تم استيراد $imported منتج بنجاح";
        if ($skipped > 0) {
            $message .= ". تم تخطي $skipped منتج (الاسم موجود مسبقاً)";
        }
        if (!empty($errors)) {
            $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " و " . (count($errors) - 5) . " أخطاء أخرى";
            }
        }
        $message .= ". وقت التنفيذ: {$executionTime} ثانية";

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}
