<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['vendor', 'orderItems'])
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        // المنتجات ذات الصلة من نفس المورد
        $relatedProducts = Product::with('vendor')
            ->where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inStock()
            ->limit(4)
            ->get();

        // المنتجات المشابهة من نفس الفئة
        $similarProducts = Product::with('vendor')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inStock()
            ->limit(4)
            ->get();

        return view('product.show', compact('product', 'relatedProducts', 'similarProducts'));
    }

    public function category($category)
    {
        $categoryName = $this->getCategoryName($category);
        
        $products = Product::with('vendor')
            ->active()
            ->byCategory($category)
            ->inStock()
            ->latest()
            ->paginate(12);

        return view('product.category', compact('products', 'category', 'categoryName'));
    }

    private function getCategoryName($category)
    {
        $categories = [
            'fruits-vegetables' => 'خضروات وفواكه',
            'dairy' => 'منتجات الألبان',
            'meat-poultry' => 'لحوم ودواجن',
            'bakery' => 'مخبوزات',
            'grocery' => 'البقالة',
            'frozen' => 'المجمدات'
        ];

        return $categories[$category] ?? $category;
    }
}
