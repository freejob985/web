<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        
        $query = Faq::active()->ordered();
        
        if ($category) {
            $query->byCategory($category);
        }
        
        $faqs = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $faqs
        ]);
    }

    public function show($id)
    {
        $faq = Faq::active()->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $faq
        ]);
    }

    public function categories()
    {
        $categories = Faq::active()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}