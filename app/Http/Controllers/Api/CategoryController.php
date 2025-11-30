<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of active categories.
     */
    public function index()
    {
        $categories = Category::active()
            ->with(['subcategories' => function($query) {
                $query->active()->ordered();
            }])
            ->ordered()
            ->get();

        // Transform image URLs and add counts
        $categoriesData = $categories->map(function ($category) {
            $category->appendImageUrls();
            $category->subcategories->transform(function ($subcategory) {
                $subcategory->appendImageUrls();
                // Add products count for each subcategory
                $subcategory->products_count = $subcategory->products()->active()->count();
                return $subcategory;
            });
            
            // Add counts to the response
            $subcategoriesCount = $category->subcategories()->active()->count();
            $productsCount = $category->products()->active()->count();
            
            $categoryData = $category->toArray();
            $categoryData['subcategories_count'] = $subcategoriesCount;
            $categoryData['products_count'] = $productsCount;
            
            return $categoryData;
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesData
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['subcategories' => function($query) {
            $query->active()->ordered();
        }]);

        // Transform image URLs
        $category->appendImageUrls();
        $category->subcategories->transform(function ($subcategory) {
            $subcategory->appendImageUrls();
            // Add products count for each subcategory
            $subcategory->products_count = $subcategory->products()->active()->count();
            return $subcategory;
        });
        
        // Add counts to the response
        $subcategoriesCount = $category->subcategories()->active()->count();
        $productsCount = $category->products()->active()->count();
        
        $categoryData = $category->toArray();
        $categoryData['subcategories_count'] = $subcategoriesCount;
        $categoryData['products_count'] = $productsCount;

        return response()->json([
            'success' => true,
            'data' => $categoryData
        ]);
    }

    /**
     * Get subcategories for a specific category.
     */
    public function subcategories(Category $category)
    {
        $subcategories = $category->subcategories()
            ->active()
            ->ordered()
            ->get();

        // Transform image URLs and add products count
        $subcategories->transform(function ($subcategory) {
            $subcategory->appendImageUrls();
            // Add products count for each subcategory
            $subcategory->products_count = $subcategory->products()->active()->count();
            return $subcategory;
        });

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * Get 5 main categories for footer.
     */
    public function mainCategories()
    {
        $categories = Category::active()
            ->ordered()
            ->limit(5)
            ->get();

        // Transform image URLs and add counts
        $categoriesData = $categories->map(function ($category) {
            $category->appendImageUrls();
            
            // Load subcategories with products count
            $category->load(['subcategories' => function($query) {
                $query->active()->ordered();
            }]);
            
            $category->subcategories->transform(function ($subcategory) {
                $subcategory->appendImageUrls();
                // Add products count for each subcategory
                $subcategory->products_count = $subcategory->products()->active()->count();
                return $subcategory;
            });
            
            $subcategoriesCount = $category->subcategories()->active()->count();
            $productsCount = $category->products()->active()->count();
            
            $categoryData = $category->toArray();
            $categoryData['subcategories_count'] = $subcategoriesCount;
            $categoryData['products_count'] = $productsCount;
            
            return $categoryData;
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesData
        ]);
    }

    /**
     * Get 5 supermarket categories for footer.
     */
    public function supermarketCategories()
    {
        $categories = Category::active()
            ->whereIn('slug', [
                'fruits-vegetables',
                'dairy',
                'meat-poultry',
                'bakery',
                'frozen'
            ])
            ->ordered()
            ->limit(5)
            ->get();

        // Transform image URLs and add counts
        $categoriesData = $categories->map(function ($category) {
            $category->appendImageUrls();
            
            // Load subcategories with products count
            $category->load(['subcategories' => function($query) {
                $query->active()->ordered();
            }]);
            
            $category->subcategories->transform(function ($subcategory) {
                $subcategory->appendImageUrls();
                // Add products count for each subcategory
                $subcategory->products_count = $subcategory->products()->active()->count();
                return $subcategory;
            });
            
            $subcategoriesCount = $category->subcategories()->active()->count();
            $productsCount = $category->products()->active()->count();
            
            $categoryData = $category->toArray();
            $categoryData['subcategories_count'] = $subcategoriesCount;
            $categoryData['products_count'] = $productsCount;
            
            return $categoryData;
        });

        return response()->json([
            'success' => true,
            'data' => $categoriesData
        ]);
    }

    /**
     * Get products for a specific category.
     */
    public function products(Request $request, $category)
    {
        // Support both ID and slug
        if (is_numeric($category)) {
            $categoryModel = Category::findOrFail($category);
        } else {
            $categoryModel = Category::where('slug', $category)
                ->orWhere('id', $category)
                ->firstOrFail();
        }
        
        $query = $categoryModel->products()
            ->with(['vendor', 'brand'])
            ->active()
            ->inStock();

        // Apply filters
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

        // Apply sorting - handle both 'sort' and 'sort_by' parameters
        $sortBy = $request->get('sort', $request->get('sort_by', 'created_at'));
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'popular':
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price-asc':
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
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
                $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);

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
                'vendor' => $product->vendor ? [
                    'id' => $product->vendor->id,
                    'name' => $product->vendor->name,
                    'rating' => $product->vendor->rating
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name
                ] : null,
                'created_at' => $product->created_at->toDateTimeString(),
                'updated_at' => $product->updated_at->toDateTimeString()
            ];
        });

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $categoryModel->id,
                'name' => $categoryModel->name_ar,
                'name_ar' => $categoryModel->name_ar,
                'name_en' => $categoryModel->name_en,
                'description' => $categoryModel->description_ar,
                'description_ar' => $categoryModel->description_ar,
                'description_en' => $categoryModel->description_en,
                'slug' => $categoryModel->slug,
                'image' => $categoryModel->image_url
            ],
            'products' => $products,
            'filters' => [
                'brands' => $categoryModel->products()
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
                            'name' => $brand->name
                        ];
                    }),
                'price_range' => [
                    'min' => $categoryModel->products()->active()->min('price'),
                    'max' => $categoryModel->products()->active()->max('price')
                ]
            ]
        ]);
    }
}
