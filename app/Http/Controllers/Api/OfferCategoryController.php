<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfferCategory;
use Illuminate\Http\Request;

class OfferCategoryController extends Controller
{
    /**
     * Display a listing of active offer categories.
     */
    public function index()
    {
        $categories = OfferCategory::active()
            ->ordered()
            ->withCount('offers')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified offer category.
     */
    public function show(OfferCategory $offerCategory)
    {
        $offerCategory->load(['offers' => function($query) {
            $query->active()->current()->ordered();
        }]);

        return response()->json([
            'success' => true,
            'data' => $offerCategory
        ]);
    }

    /**
     * Get offers for a specific category.
     */
    public function offers(OfferCategory $offerCategory)
    {
        $offers = $offerCategory->offers()
            ->active()
            ->current()
            ->ordered()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $offers
        ]);
    }
}
