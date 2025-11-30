<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMethod;
use Illuminate\Http\Request;

class ContactMethodController extends Controller
{
    public function index()
    {
        $contactMethods = ContactMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $contactMethods
        ]);
    }
}