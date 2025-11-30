<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VendorProductController extends Controller
{
    /**
     * Get vendor products with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $query = Product::where('vendor_id', $vendorId);

            // Apply filters
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            switch ($sortBy) {
                case 'name':
                    $query->orderBy('name', $sortDirection);
                    break;
                case 'price':
                    $query->orderBy('price', $sortDirection);
                    break;
                case 'rating':
                    $query->orderBy('rating', $sortDirection);
                    break;
                case 'sales':
                    $query->withCount(['orderItems as sales_count' => function($q) {
                        $q->whereHas('order', function($orderQuery) {
                            $orderQuery->where('status', '!=', 'cancelled');
                        });
                    }])->orderBy('sales_count', $sortDirection);
                    break;
                default:
                    $query->orderBy('created_at', $sortDirection);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $products = $query->paginate($perPage);

            // Transform products
            $transformedProducts = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'original_price' => $product->original_price,
                    'stock' => $product->stock,
                    'sku' => $product->sku,
                    'image' => $product->image,
                    'images' => $product->images,
                    'category' => $product->category->name_ar ?? '',
                    'subcategory' => $product->subcategory->name_ar ?? '',
                    'brand' => $product->brand,
                    'rating' => $product->rating,
                    'reviews_count' => $product->reviews_count,
                    'sales_count' => $product->sales_count ?? 0,
                    'is_fresh' => $product->is_fresh,
                    'is_featured' => $product->is_featured,
                    'is_active' => $product->is_active,
                    'status' => $product->status,
                    'created_at' => $product->created_at->toISOString(),
                    'updated_at' => $product->updated_at->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'products' => [
                    'data' => $transformedProducts,
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
                'filters' => [
                    'categories' => Category::select('id', 'name_ar as name')->get(),
                    'statuses' => ['active', 'inactive', 'pending', 'rejected']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new product
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            // Convert category_slug to category_id if provided
            $requestData = $request->all();
            if (isset($requestData['category_slug']) && !isset($requestData['category_id'])) {
                $category = \App\Models\Category::where('slug', $requestData['category_slug'])->first();
                if ($category) {
                    $requestData['category_id'] = $category->id;
                    unset($requestData['category_slug']);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Category not found with slug: ' . $requestData['category_slug']
                    ], 422);
                }
            }

            $validator = Validator::make($requestData, [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'original_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'brand' => 'nullable|string|max:255',
                'sku' => 'nullable|string|max:255|unique:products,sku',
                'weight' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'origin' => 'nullable|string|max:255',
                'governorate_id' => 'nullable|exists:governorates,id',
                'city_id' => 'nullable|exists:cities,id',
                'expiry_date' => 'nullable|date',
                'nutritional_info' => 'nullable|string',
                'is_fresh' => 'boolean',
                'is_organic' => 'boolean',
                'is_local' => 'boolean',
                'is_halal' => 'boolean',
                'is_featured' => 'boolean',
                'is_new' => 'boolean',
                'is_bestseller' => 'boolean',
                'search_keywords' => 'nullable|string|max:500',
                'meta_description' => 'nullable|string|max:160',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productData = $requestData;
            $productData['vendor_id'] = $vendorId;
            $productData['status'] = 'pending'; // Products need approval
            $productData['is_active'] = false; // Not active until approved
            
            // Generate SKU if not provided
            if (empty($productData['sku'])) {
                $timestamp = substr(time(), -6);
                $namePrefix = strtoupper(substr(preg_replace('/\s+/', '', $productData['name']), 0, 3));
                $categoryPrefix = 'CAT' . $productData['category_id'];
                $productData['sku'] = $categoryPrefix . '-' . $namePrefix . '-' . $timestamp;
            }
            
            // Log the data being saved
            \Log::info('Product data before save:', $productData);
            
            // Validate SKU uniqueness after generation
            if (empty($productData['sku'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'SKU is required'
                ], 422);
            }

            // Handle image upload
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    // تخزين المسار المباشر بدون /storage/ لأن UrlHelper سيضيفها لاحقاً
                    $images[] = $path;
                }
                $productData['images'] = $images;
                $productData['image'] = $images[0] ?? null; // First image as main image
            }

            $product = Product::create($productData);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully. It will be reviewed before being published.',
                'product' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific product
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $product = Product::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->with(['category', 'subcategory'])
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            return response()->json([
                'success' => true,
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $product = Product::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'original_price' => 'nullable|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'brand' => 'nullable|string|max:255',
                'sku' => 'nullable|string|max:255|unique:products,sku,' . $id,
                'weight' => 'nullable|numeric|min:0',
                'unit' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'origin' => 'nullable|string|max:255',
                'governorate_id' => 'nullable|exists:governorates,id',
                'city_id' => 'nullable|exists:cities,id',
                'expiry_date' => 'nullable|date',
                'nutritional_info' => 'nullable|string',
                'is_fresh' => 'boolean',
                'is_organic' => 'boolean',
                'is_local' => 'boolean',
                'is_halal' => 'boolean',
                'is_featured' => 'boolean',
                'is_new' => 'boolean',
                'is_bestseller' => 'boolean',
                'search_keywords' => 'nullable|string|max:500',
                'meta_description' => 'nullable|string|max:160',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $productData = $request->all();

            // Handle image upload
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    // تخزين المسار المباشر بدون /storage/ لأن UrlHelper سيضيفها لاحقاً
                    $images[] = $path;
                }
                $productData['images'] = $images;
                $productData['image'] = $images[0] ?? $product->image;
            }

            // If product is being updated, it needs re-approval
            if ($product->status === 'approved') {
                $productData['status'] = 'pending';
                $productData['is_active'] = false;
            }

            $product->update($productData);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $vendorId = $this->getVendorId($request);
            if (!$vendorId) {
                return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
            }

            $product = Product::where('id', $id)
                ->where('vendor_id', $vendorId)
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            // Check if product has orders
            $hasOrders = $product->orderItems()->exists();
            if ($hasOrders) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete product that has been ordered. You can deactivate it instead.'
                ], 400);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor ID from authenticated user
     */
    private function getVendorId(Request $request): ?int
    {
        // Try to get from authenticated vendor via guard
        $vendor = $request->user('vendor');
        if ($vendor) {
            \Log::info('Vendor authenticated via guard', ['vendor_id' => $vendor->id]);
            return $vendor->id;
        }

        // Try to get from Auth facade as fallback
        $vendor = \Illuminate\Support\Facades\Auth::guard('vendor')->user();
        if ($vendor) {
            \Log::info('Vendor authenticated via Auth facade', ['vendor_id' => $vendor->id]);
            return $vendor->id;
        }

        \Log::warning('Vendor authentication failed - no vendor found');
        return null;
    }
}
