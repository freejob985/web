<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
    
    public function show($id)
    {
        $category = BusinessCategory::findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }
}