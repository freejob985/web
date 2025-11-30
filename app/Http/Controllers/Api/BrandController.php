<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of brands for API
     */
    public function index(Request $request)
    {
        $query = Brand::where('is_active', true);
        
        // Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Add sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'created_at', 'products_count'])) {
            if ($sortBy === 'products_count') {
                $query->withCount('products')->orderBy('products_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('name', 'asc');
        }
        
        // Get brands with product count
        $brands = $query->withCount('products')->get();
        
        // Transform the data
        $brandsData = $brands->map(function($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'name_ar' => $brand->name,
                'description' => $brand->description,
                'logo_url' => $brand->logo ? asset('storage/' . $brand->logo) : null,
                'website' => $brand->website,
                'country' => $brand->country,
                'products_count' => $brand->products_count,
                'is_active' => $brand->is_active,
                'created_at' => $brand->created_at,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $brandsData,
            'total' => $brandsData->count()
        ]);
    }
    
    /**
     * Display the specified brand
     */
    public function show($id)
    {
        $brand = Brand::where('is_active', true)
            ->withCount('products')
            ->find($id);
            
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $brand->id,
                'name' => $brand->name,
                'name_ar' => $brand->name,
                'description' => $brand->description,
                'logo_url' => $brand->logo ? asset('storage/' . $brand->logo) : null,
                'website' => $brand->website,
                'country' => $brand->country,
                'products_count' => $brand->products_count,
                'is_active' => $brand->is_active,
                'created_at' => $brand->created_at,
            ]
        ]);
    }
    
    /**
     * Get products for a specific brand
     */
    public function products($id, Request $request)
    {
        $brand = Brand::where('is_active', true)->find($id);
        
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }
        
        $query = $brand->products()->where('is_active', true);
        
        // Add sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['name', 'price', 'created_at', 'rating'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        // Pagination
        $perPage = min($request->get('per_page', 12), 50);
        $products = $query->with(['vendor', 'category'])->paginate($perPage);
        
        // Transform products data
        $products->getCollection()->transform(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'stock' => $product->stock,
                'rating' => $product->rating ?? 0,
                'reviews_count' => $product->reviews_count ?? 0,
                'image_url' => $product->getMainImage(),
                'vendor' => $product->vendor ? [
                    'id' => $product->vendor->id,
                    'name' => $product->vendor->name
                ] : null,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name_ar ?? $product->category->name
                ] : null,
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
            ],
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
                'name_ar' => $brand->name,
            ]
        ]);
    }
}
