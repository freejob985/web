<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of active cities.
     */
    public function index(Request $request)
    {
        $query = City::active()->with('governorate');

        if ($request->has('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        $cities = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Display the specified city.
     */
    public function show(City $city)
    {
        $city->load('governorate');

        return response()->json([
            'success' => true,
            'data' => $city
        ]);
    }
}
