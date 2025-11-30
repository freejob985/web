<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all active governorates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGovernorates()
    {
        $governorates = Governorate::active()->ordered()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $governorates
        ]);
    }

    /**
     * Get cities, optionally filtered by governorate
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $query = City::active()->ordered();
        
        // Filter by governorate if provided
        if ($request->has('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }
        
        $cities = $query->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $cities
        ]);
    }
}