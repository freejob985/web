<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::with(['governorate'])
            ->withCount(['products', 'vendors'])
            ->ordered()
            ->paginate(20);

        return view('admin.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $governorates = Governorate::active()->ordered()->get();
        return view('admin.cities.create', compact('governorates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:cities,code',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'governorate_id' => 'required|exists:governorates,id'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        City::create($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم إنشاء المدينة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city->load(['governorate', 'products', 'vendors']);
        return view('admin.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        $governorates = Governorate::active()->ordered()->get();
        return view('admin.cities.edit', compact('city', 'governorates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:cities,code,' . $city->id,
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'governorate_id' => 'required|exists:governorates,id'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $city->update($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم تحديث المدينة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        if ($city->products()->count() > 0 || $city->vendors()->count() > 0) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'لا يمكن حذف المدينة لوجود منتجات أو موردين مرتبطين بها');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم حذف المدينة بنجاح');
    }
}
