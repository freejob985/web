<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of active governorates.
     */
    public function index()
    {
        $governorates = Governorate::active()
            ->with(['cities' => function($query) {
                $query->active()->ordered();
            }])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $governorates
        ]);
    }

    /**
     * Display the specified governorate.
     */
    public function show(Governorate $governorate)
    {
        $governorate->load(['cities' => function($query) {
            $query->active()->ordered();
        }]);

        return response()->json([
            'success' => true,
            'data' => $governorate
        ]);
    }

    /**
     * Get cities for a specific governorate.
     */
    public function cities(Governorate $governorate)
    {
        $cities = $governorate->cities()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }
}
