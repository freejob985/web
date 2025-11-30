<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function featured()
    {
        $items = Product::with('vendor')
            ->active()->featured()->inStock()
            ->orderByDesc('sales_count')
            ->limit(8)->get();
        
        // Transform image URLs
        $items->transform(function ($product) {
            return $product->appendImageUrls();
        });
        
        return response()->json($items);
    }

    public function fresh()
    {
        $items = Product::with('vendor')
            ->active()->fresh()->inStock()
            ->latest()
            ->paginate(12);
        
        // Transform image URLs
        $items->getCollection()->transform(function ($product) {
            return $product->appendImageUrls();
        });
        
        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function offers()
    {
        $items = Product::with('vendor')
            ->active()->inStock()
            ->whereNotNull('original_price')
            ->whereColumn('original_price', '>', 'price')
            ->orderByRaw('((original_price - price) / original_price) DESC')
            ->paginate(12);
        
        // Transform image URLs
        $items->getCollection()->transform(function ($product) {
            return $product->appendImageUrls();
        });
        
        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function show(int $id)
    {
        try {
            $product = Product::with(['vendor', 'orderItems'])->active()->findOrFail($id);

            $related = Product::with('vendor')
                ->where('vendor_id', $product->vendor_id)
                ->where('id', '!=', $product->id)
                ->active()->inStock()->limit(6)->get();

            $similar = Product::with('vendor')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->active()->inStock()->limit(6)->get();

            // Transform image URLs for all products
            $product->appendImageUrls();
            
            $related->transform(function ($product) {
                return $product->appendImageUrls();
            });
            
            $similar->transform(function ($product) {
                return $product->appendImageUrls();
            });

            return response()->json([
                'product' => $product,
                'related' => $related,
                'similar' => $similar,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found or not available',
                'message' => 'المنتج غير موجود أو غير متاح للعرض. قد يكون المنتج في انتظار الموافقة أو غير نشط.',
                'code' => 'PRODUCT_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the product',
                'message' => 'حدث خطأ أثناء جلب بيانات المنتج',
                'code' => 'PRODUCT_FETCH_ERROR'
            ], 500);
        }
    }
}
