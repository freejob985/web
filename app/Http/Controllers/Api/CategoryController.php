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
     * 
     * @param Request $request
     * @param Category|string|int $category - Can be Category model (from route binding), slug string, or ID
     */
    public function products(Request $request, $category)
    {
        // Handle different input types: Category model, slug string, or ID
        if ($category instanceof Category) {
            // Route model binding found the category
            $categoryModel = $category;
        } elseif (is_numeric($category)) {
            // Category is an ID
            $categoryModel = Category::find($category);
        } else {
            // Category is a slug string
            $categoryModel = Category::where('slug', $category)->first();
        }
        
        // If category not found, return 404 with proper error message
        if (!$categoryModel) {
            return response()->json([
                'message' => 'المورد المطلوب غير موجود.',
                'error' => 'Category not found'
            ], 404);
        }
        
        $query = $categoryModel->products()
            ->with(['vendor', 'brand'])
            ->active();

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
        $sortBy = $request->get('sort', $request->get('sort_by', 'popular'));
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('sales_count', 'desc')->orderBy('rating', 'desc');
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
            case 'newest':
            case 'created_at':
            default:
                $query->orderBy('created_at', 'desc');
        }

        $perPage = (int) $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Transform image URLs using the same method as CatalogController
        $products->getCollection()->transform(function ($product) {
            return $product->appendImageUrls();
        });

        // Return response in the format expected by frontend
        return response()->json([
            'category' => [
                'slug' => $categoryModel->slug,
                'name' => $categoryModel->name_ar,
            ],
            'data' => $products->items(),
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }
}


