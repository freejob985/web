<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferCategory;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of active offers.
     */
    public function index(Request $request)
    {
        $query = Offer::with('category')
            ->active()
            ->current()
            ->ordered();

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('offer_category_id', $request->category_id);
        }

        // Filter by popular offers
        if ($request->has('popular') && $request->popular) {
            $query->popular();
        }

        // Filter by flash sale offers
        if ($request->has('flash_sale') && $request->flash_sale) {
            $query->flashSale();
        }

        $offers = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }

    /**
     * Display the specified offer.
     */
    public function show(Offer $offer)
    {
        $offer->load('category');

        return response()->json([
            'success' => true,
            'data' => $offer
        ]);
    }

    /**
     * Get featured offers (popular offers).
     */
    public function featured()
    {
        $offers = Offer::with('category')
            ->active()
            ->current()
            ->where('is_featured', true)
            ->ordered()
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }

    /**
     * Get flash sale offers.
     */
    public function flashSale()
    {
        $offers = Offer::with('category')
            ->active()
            ->current()
            ->where('is_limited_time', true)
            ->ordered()
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }

    /**
     * Get offers by category.
     */
    public function byCategory(OfferCategory $category)
    {
        $offers = $category->offers()
            ->active()
            ->current()
            ->ordered()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $offers,
            'category' => $category
        ]);
    }

    /**
     * Search offers.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال كلمة البحث'
            ], 400);
        }

        $offers = Offer::with('category')
            ->active()
            ->current()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->ordered()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $offers,
            'query' => $query
        ]);
    }
}
