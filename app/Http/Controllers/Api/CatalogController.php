<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    private array $categories = [
        'fruits-vegetables' => 'خضروات وفواكه',
        'dairy' => 'منتجات الألبان',
        'meat-poultry' => 'لحوم ودواجن',
        'bakery' => 'مخبوزات',
        'grocery' => 'البقالة',
        'frozen' => 'المجمدات'
    ];

    public function categories()
    {
        $result = [];
        foreach ($this->categories as $slug => $name) {
            $result[] = [
                'name' => $name,
                'slug' => $slug,
                'products_count' => Product::byCategory($slug)->active()->count()
            ];
        }
        return response()->json($result);
    }

    public function categoryProducts(Request $request, string $slug)
    {
        $query = Product::with('vendor')
            ->active()
            ->byCategory($slug)
            ->inStock();

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
        }

        $perPage = (int) $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Transform image URLs
        $products->getCollection()->transform(function ($product) {
            return $product->appendImageUrls();
        });

        return response()->json([
            'category' => [
                'slug' => $slug,
                'name' => $this->categories[$slug] ?? $slug,
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

    public function search(Request $request)
    {
        $q = $request->get('q');
        $category = $request->get('category');
        $subcategory = $request->get('subcategory');
        $vendor = $request->get('vendor');
        $brand = $request->get('brand');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $rating = $request->get('rating');
        $available = $request->get('available');
        $sort = $request->get('sort', 'relevance');

        $query = Product::with(['vendor', 'category', 'subcategory', 'brand'])->active();

        // Search query
        if ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
                   ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($category) {
            if (is_numeric($category)) {
                $query->where('category_id', $category);
            } else {
                $query->byCategory($category);
            }
        }

        // Subcategory filter
        if ($subcategory) {
            $query->where('subcategory_id', $subcategory);
        }

        // Vendor filter (legacy support)
        if ($vendor) {
            if (is_numeric($vendor)) {
                $query->where('vendor_id', $vendor);
            } else {
                $query->byVendor($vendor);
            }
        }

        // Brand filter
        if ($brand) {
            $query->where('brand_id', $brand);
        }

        // Price range filter
        if ($minPrice !== null && is_numeric($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null && is_numeric($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        // Rating filter
        if ($rating && is_numeric($rating)) {
            $query->where('rating', '>=', $rating);
        }

        // Availability filter
        if ($available === 'true' || $available === '1') {
            $query->where('stock', '>', 0);
        }

        // Sorting
        switch ($sort) {
            case 'price-low':
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'relevance':
            default:
                if ($q) {
                    // If there's a search query, order by relevance (name match first, then description)
                    $query->orderByRaw("CASE 
                        WHEN name LIKE ? THEN 1 
                        WHEN description LIKE ? THEN 2 
                        ELSE 3 
                    END", ["%{$q}%", "%{$q}%"]);
                } else {
                    // Default to sales count for general browsing
                    $query->orderBy('sales_count', 'desc');
                }
        }

        $perPage = (int) $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Transform image URLs and add additional data
        $products->getCollection()->transform(function ($product) {
            $transformed = $product->appendImageUrls();
            
            // Add brand information
            if ($product->brand) {
                $transformed['brand'] = [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                    'name_ar' => $product->brand->name,
                ];
            }
            
            // Add category information
            if ($product->category) {
                $transformed['category'] = [
                    'id' => $product->category->id,
                    'name' => $product->category->name_ar,
                    'name_ar' => $product->category->name_ar,
                ];
            }
            
            // Add subcategory information
            if ($product->subcategory) {
                $transformed['subcategory'] = [
                    'id' => $product->subcategory->id,
                    'name' => $product->subcategory->name_ar,
                    'name_ar' => $product->subcategory->name_ar,
                ];
            }
            
            return $transformed;
        });

        return response()->json([
            'query' => $q,
            'filters' => [
                'category' => $category,
                'subcategory' => $subcategory,
                'vendor' => $vendor,
                'brand' => $brand,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'rating' => $rating,
                'available' => $available,
                'sort' => $sort,
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
