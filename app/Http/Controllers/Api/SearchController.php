<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 16); // 16 products per page (4 rows x 4 columns)
        
        // Filter parameters
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $categoryId = $request->get('category');
        $subcategoryId = $request->get('subcategory');
        $brandId = $request->get('brand');
        $productId = $request->get('product_id');
        $rating = $request->get('rating');
        $available = $request->get('available');
        $sort = $request->get('sort', 'relevance');

        // Build products query
        $productsQuery = Product::with(['vendor', 'category', 'subcategory', 'brand'])
            ->where('is_active', true);
        
        // Apply search query if provided
        if (!empty($query)) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            });
        }

        // Apply filters
        if ($minPrice !== null) {
            $productsQuery->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $productsQuery->where('price', '<=', $maxPrice);
        }
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        if ($subcategoryId) {
            $productsQuery->where('subcategory_id', $subcategoryId);
        }
        if ($brandId) {
            $productsQuery->where('brand_id', $brandId);
        }
        if ($productId) {
            $productsQuery->where('id', $productId);
        }
        if ($rating) {
            $productsQuery->where('rating', '>=', $rating);
        }
        if ($available === 'true' || $available === true) {
            $productsQuery->where('stock', '>', 0);
        }

        // Apply sorting
        switch ($sort) {
            case 'price-low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price-high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'rating':
                $productsQuery->orderBy('rating', 'desc');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'relevance':
            default:
                $productsQuery->orderBy('is_featured', 'desc')
                             ->orderBy('rating', 'desc')
                             ->orderBy('created_at', 'desc');
                break;
        }

        // Execute the query and paginate
        $products = $productsQuery->paginate($perPage, ['*'], 'page', $page);
        
        // Transform products for frontend
        $transformedProducts = $products->getCollection()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'image' => $product->getMainImage(),
                'image_url' => $product->getMainImage(),
                'vendor' => $product->vendor ? [
                    'id' => $product->vendor->id,
                    'name' => $product->vendor->name
                ] : null,
                'category' => $product->category ? $product->category->name_ar : null,
                'subcategory' => $product->subcategory ? $product->subcategory->name_ar : null,
                'brand' => $product->brand ? $product->brand->name : null,
                'rating' => $product->rating ?? 0,
                'reviews_count' => $product->reviews_count ?? 0,
                'stock' => $product->stock,
                'is_fresh' => $product->is_fresh ?? false,
                'is_featured' => $product->is_featured ?? false,
                'sku' => $product->sku
            ];
        })->toArray();

        // Return products in the expected format for frontend
        return response()->json([
            'data' => $transformedProducts,
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');
        $brand = $request->get('brand');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $limit = $request->get('limit', 16); // Changed to 16 products per page
        $page = $request->get('page', 1);

        $productsQuery = Product::with(['vendor', 'category', 'subcategory', 'brand'])
            ->where('is_active', true);

        // Search query - if no query, show all products
        if (!empty($query)) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            });
        }

        // Category filter
        if ($category) {
            $productsQuery->where('category_id', $category);
        }

        // Brand filter
        if ($brand) {
            $productsQuery->where('brand_id', $brand);
        }

        // Price range filter
        if ($minPrice !== null) {
            $productsQuery->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $productsQuery->where('price', '<=', $maxPrice);
        }

        // Sorting
        $productsQuery->orderBy($sortBy, $sortOrder);

        $products = $productsQuery->paginate($limit, ['*'], 'page', $page);

        $products->getCollection()->transform(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'image' => $product->getMainImage(),
                'image_url' => $product->getMainImage(),
                'vendor' => $product->vendor ? $product->vendor->name : null,
                'category' => $product->category ? $product->category->name_ar : null,
                'brand' => $product->brand ? $product->brand->name : null,
                'rating' => $product->rating,
                'reviews_count' => $product->reviews_count,
                'stock' => $product->stock,
                'is_fresh' => $product->is_fresh,
                'is_featured' => $product->is_featured,
                'discount_percentage' => $product->discount_percentage,
                'is_on_sale' => $product->is_on_sale,
                'formatted_price' => $product->formatted_price,
                'in_stock' => $product->stock > 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }

    public function getSearchFilters(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('name_ar')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name_ar,
                    'name_ar' => $category->name_ar,
                    'products_count' => $category->products_count
                ];
            });

        $brands = Brand::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get()
            ->map(function($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'name_ar' => $brand->name,
                    'products_count' => $brand->products_count
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'brands' => $brands
            ]
        ]);
    }

    public function getPopularSearches(Request $request)
    {
        // This would typically come from a search analytics table
        // For now, return some popular search terms
        $popularSearches = [
            'تفاح',
            'خيار',
            'طماطم',
            'حليب',
            'خبز',
            'جبن',
            'دجاج',
            'لحم',
            'أرز',
            'زيت'
        ];

        return response()->json([
            'success' => true,
            'data' => $popularSearches
        ]);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 4);

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0
            ]);
        }

        $results = [];

        // Search Products with better ranking and filtering
        $products = Product::with(['category', 'brand', 'vendor'])
            ->where('is_active', true)
            ->where('stock', '>', 0) // Only show products in stock
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->orderBy('is_featured', 'desc') // Featured products first
            ->orderBy('rating', 'desc') // Higher rated products first
            ->orderBy('sales_count', 'desc') // Popular products first
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return [
                    'id' => (int) $product->id,
                    'name' => (string) $product->name,
                    'type' => 'product',
                    'image' => $product->getMainImage(),
                    'price' => number_format((float) $product->price, 3) . ' د.ك',
                    'original_price' => $product->original_price ? number_format((float) $product->original_price, 3) . ' د.ك' : null,
                    'category' => $product->category ? (string) $product->category->name_ar : null,
                    'brand' => $product->brand ? (string) $product->brand->name : null,
                    'vendor' => $product->vendor ? (string) $product->vendor->name : null,
                    'rating' => is_numeric($product->rating) ? (float) $product->rating : 0,
                    'reviews_count' => is_numeric($product->reviews_count) ? (int) $product->reviews_count : 0,
                    'stock' => (int) $product->stock,
                    'is_fresh' => (bool) ($product->is_fresh ?? false),
                    'is_featured' => (bool) ($product->is_featured ?? false),
                    'url' => "/product/{$product->id}"
                ];
            });

        $results = array_merge($results, $products->toArray());

        // If we don't have enough products, search for brands
        if (count($results) < $limit) {
            $brands = Brand::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->limit($limit - count($results))
                ->get()
                ->map(function($brand) {
                    return [
                        'id' => (int) $brand->id,
                        'name' => (string) $brand->name,
                        'type' => 'brand',
                        'image' => $brand->logo_url,
                        'description' => (string) ($brand->description ?? ''),
                        'url' => "/brands/{$brand->id}"
                    ];
                });

            $results = array_merge($results, $brands->toArray());
        }

        // If we still don't have enough results, search for categories
        if (count($results) < $limit) {
            $categories = Category::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name_ar', 'like', "%{$query}%")
                      ->orWhere('name_en', 'like', "%{$query}%");
                })
                ->limit($limit - count($results))
                ->get()
                ->map(function($category) {
                    return [
                        'id' => (int) $category->id,
                        'name' => (string) $category->name_ar,
                        'type' => 'category',
                        'image' => $category->image_url,
                        'description' => (string) ($category->description_ar ?? ''),
                        'url' => "/categories/{$category->slug}"
                    ];
                });

            $results = array_merge($results, $categories->toArray());
        }

        return response()->json([
            'results' => array_slice($results, 0, $limit),
            'total' => count($results)
        ]);
    }
}