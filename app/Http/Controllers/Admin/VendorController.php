<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->paginate(20);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $governorates = Governorate::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        return view('admin.vendors.create', compact('governorates', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'governorate' => 'nullable|string',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'status' => 'nullable|in:pending,approved,rejected',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'is_fresh' => 'sometimes|boolean',
            // New fields
            'postal_code' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'commercial_record' => 'nullable|string|unique:vendors,commercial_record',
            'tax_number' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'delivery_fee' => 'nullable|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);
        $data['password'] = bcrypt($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_fresh'] = $request->boolean('is_fresh');
        $data['status'] = $data['status'] ?? 'approved';
        Vendor::create($data);
        return redirect()->route('admin.vendors.index')->with('success', 'تم إنشاء المورد');
    }

    public function edit(Vendor $vendor)
    {
        $governorates = Governorate::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        return view('admin.vendors.edit', compact('vendor', 'governorates', 'cities'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'governorate' => 'nullable|string',
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'status' => 'nullable|in:pending,approved,rejected',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'is_fresh' => 'sometimes|boolean',
            // New fields
            'postal_code' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'commercial_record' => 'nullable|string|unique:vendors,commercial_record,' . $vendor->id,
            'tax_number' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'delivery_fee' => 'nullable|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_fresh'] = $request->boolean('is_fresh');
        $vendor->update($data);
        return redirect()->route('admin.vendors.index')->with('success', 'تم تحديث بيانات المورد');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'تم حذف المورد');
    }
}
