<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // المنتجات المميزة
        $featuredProducts = Product::with('vendor')
            ->active()
            ->featured()
            ->inStock()
            ->limit(8)
            ->get();

        // المنتجات الطازجة
        $freshProducts = Product::with('vendor')
            ->active()
            ->fresh()
            ->inStock()
            ->limit(6)
            ->get();

        // العروض (المنتجات التي لها سعر أصلي أعلى)
        $saleProducts = Product::with('vendor')
            ->active()
            ->whereNotNull('original_price')
            ->whereColumn('original_price', '>', 'price')
            ->inStock()
            ->limit(6)
            ->get();

        // الموردين النشطين
        $vendors = Vendor::active()
            ->approved()
            ->withCount('products')
            ->limit(4)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'freshProducts', 
            'saleProducts',
            'vendors'
        ));
    }

    public function categories()
    {
        $categories = [
            [
                'name' => 'خضروات وفواكه',
                'slug' => 'fruits-vegetables',
                'image' => 'images/categories/fruits-vegetables.jpg',
                'description' => 'خضروات وفواكه طازجة يومياً',
                'products_count' => Product::byCategory('fruits-vegetables')->active()->count()
            ],
            [
                'name' => 'منتجات الألبان',
                'slug' => 'dairy',
                'image' => 'images/categories/dairy.jpg',
                'description' => 'منتجات ألبان طازجة وصحية',
                'products_count' => Product::byCategory('dairy')->active()->count()
            ],
            [
                'name' => 'لحوم ودواجن',
                'slug' => 'meat-poultry',
                'image' => 'images/categories/meat-poultry.jpg',
                'description' => 'لحوم ودواجن طازجة وعالية الجودة',
                'products_count' => Product::byCategory('meat-poultry')->active()->count()
            ],
            [
                'name' => 'مخبوزات',
                'slug' => 'bakery',
                'image' => 'images/categories/bakery.jpg',
                'description' => 'مخبوزات طازجة يومياً',
                'products_count' => Product::byCategory('bakery')->active()->count()
            ],
            [
                'name' => 'البقالة',
                'slug' => 'grocery',
                'image' => 'images/categories/grocery.jpg',
                'description' => 'مواد غذائية ومنتجات أساسية',
                'products_count' => Product::byCategory('grocery')->active()->count()
            ],
            [
                'name' => 'المجمدات',
                'slug' => 'frozen',
                'image' => 'images/categories/frozen.jpg',
                'description' => 'منتجات مجمدة عالية الجودة',
                'products_count' => Product::byCategory('frozen')->active()->count()
            ]
        ];

        return view('categories', compact('categories'));
    }

    public function offers()
    {
        // العروض والخصومات
        $offers = Product::with('vendor')
            ->active()
            ->whereNotNull('original_price')
            ->whereColumn('original_price', '>', 'price')
            ->inStock()
            ->orderByRaw('((original_price - price) / original_price) DESC')
            ->paginate(12);

        return view('offers', compact('offers'));
    }

    public function fresh()
    {
        // المنتجات الطازجة
        $freshProducts = Product::with('vendor')
            ->active()
            ->fresh()
            ->inStock()
            ->latest()
            ->paginate(12);

        // الموردين المتخصصين في المنتجات الطازجة
        $freshVendors = Vendor::active()
            ->approved()
            ->whereHas('products', function($query) {
                $query->where('is_fresh', true)->where('is_active', true);
            })
            ->withCount(['products' => function($query) {
                $query->where('is_fresh', true)->where('is_active', true);
            }])
            ->limit(6)
            ->get();

        return view('fresh', compact('freshProducts', 'freshVendors'));
    }

    public function delivery()
    {
        return view('delivery');
    }

    public function contact()
    {
        return view('contact');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $vendor = $request->get('vendor');
        $sort = $request->get('sort', 'relevance');

        $products = Product::with('vendor')
            ->active()
            ->when($query, function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($category, function($q) use ($category) {
                $q->where('category', $category);
            })
            ->when($vendor, function($q) use ($vendor) {
                $q->where('vendor_id', $vendor);
            });

        // الترتيب
        switch ($sort) {
            case 'price_low':
                $products->orderBy('price', 'asc');
                break;
            case 'price_high':
                $products->orderBy('price', 'desc');
                break;
            case 'rating':
                $products->orderBy('rating', 'desc');
                break;
            case 'newest':
                $products->latest();
                break;
            default:
                $products->orderBy('sales_count', 'desc');
        }

        $products = $products->paginate(12)->withQueryString();

        return view('search', compact('products', 'query', 'category', 'vendor', 'sort'));
    }
}
