<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of active subcategories.
     */
    public function index(Request $request)
    {
        $query = Subcategory::active()->with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $subcategories = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * Display the specified subcategory.
     */
    public function show(Subcategory $subcategory)
    {
        $subcategory->load('category');

        return response()->json([
            'success' => true,
            'data' => $subcategory
        ]);
    }
}
