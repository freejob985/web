<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Get all active sliders
     */
    public function index()
    {
        $sliders = Slider::active()
            ->ordered()
            ->get();

        // Transform image URLs
        $sliders->transform(function ($slider) {
            return $slider->appendImageUrls();
        });

        return response()->json([
            'success' => true,
            'data' => $sliders,
            'message' => 'تم جلب السلايدرات بنجاح'
        ]);
    }

    /**
     * Get a specific slider
     */
    public function show(Slider $slider)
    {
        if (!$slider->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'السلايدر غير متاح'
            ], 404);
        }

        // Transform image URLs
        $slider->appendImageUrls();

        return response()->json([
            'success' => true,
            'data' => $slider,
            'message' => 'تم جلب السلايدر بنجاح'
        ]);
    }
}
