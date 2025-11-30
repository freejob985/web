<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $governorates = Governorate::withCount(['cities', 'products', 'vendors'])
            ->ordered()
            ->paginate(20);

        return view('admin.governorates.index', compact('governorates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.governorates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:governorates,code',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Governorate::create($data);

        return redirect()->route('admin.governorates.index')
            ->with('success', 'تم إنشاء المحافظة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Governorate $governorate)
    {
        $governorate->load(['cities', 'products', 'vendors']);
        return view('admin.governorates.show', compact('governorate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Governorate $governorate)
    {
        return view('admin.governorates.edit', compact('governorate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Governorate $governorate)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:governorates,code,' . $governorate->id,
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $governorate->update($data);

        return redirect()->route('admin.governorates.index')
            ->with('success', 'تم تحديث المحافظة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Governorate $governorate)
    {
        if ($governorate->cities()->count() > 0) {
            return redirect()->route('admin.governorates.index')
                ->with('error', 'لا يمكن حذف المحافظة لوجود مدن مرتبطة بها');
        }

        $governorate->delete();

        return redirect()->route('admin.governorates.index')
            ->with('success', 'تم حذف المحافظة بنجاح');
    }

    /**
     * Get cities for a specific governorate
     */
    public function getCities(Governorate $governorate)
    {
        $cities = $governorate->cities()
            ->where('is_active', true)
            ->orderBy('name_ar')
            ->get(['id', 'name_ar']);

        return response()->json($cities);
    }
}
