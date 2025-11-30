<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['data' => []]);
        return response()->json([
            'data' => $user->addresses()->orderByDesc('is_default')->get()->map(function ($a) {
                return [
                    'id' => $a->id,
                    'label' => $a->title,  // استخدام label بدلاً من title للتوافق مع التطبيق
                    'title' => $a->title,  // الإبقاء على title للتوافق
                    'address' => $a->address,
                    'city' => $a->city,
                    'governorate' => $a->governorate,
                    'phone' => $a->phone,
                    'is_default' => (bool) $a->is_default,
                ];
            })->values(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'governorate' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'is_default' => 'boolean',
        ]);
        
        // تحويل label إلى title للقاعدة
        $addressData = [
            'title' => $data['label'],
            'address' => $data['address'],
            'city' => $data['city'] ?? null,
            'governorate' => $data['governorate'] ?? null,
            'phone' => $data['phone'] ?? null,
            'is_default' => $data['is_default'] ?? false,
        ];
        
        if (!empty($addressData['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }
        $addr = $user->addresses()->create($addressData);
        return response()->json(['success' => true, 'id' => $addr->id]);
    }

    public function update(Request $request, int $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $addr = $user->addresses()->findOrFail($id);
        $data = $request->validate([
            'label' => 'sometimes|string|max:100',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'governorate' => 'sometimes|string',
            'phone' => 'nullable|string|max:30',
            'is_default' => 'boolean',
        ]);
        
        // تحويل label إلى title للقاعدة
        $addressData = [];
        if (isset($data['label'])) {
            $addressData['title'] = $data['label'];
        }
        if (isset($data['address'])) {
            $addressData['address'] = $data['address'];
        }
        if (isset($data['city'])) {
            $addressData['city'] = $data['city'];
        }
        if (isset($data['governorate'])) {
            $addressData['governorate'] = $data['governorate'];
        }
        if (isset($data['phone'])) {
            $addressData['phone'] = $data['phone'];
        }
        if (isset($data['is_default'])) {
            $addressData['is_default'] = $data['is_default'];
        }
        
        if (!empty($addressData['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }
        $addr->update($addressData);
        return response()->json(['success' => true]);
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $addr = $user->addresses()->findOrFail($id);
        $addr->delete();
        return response()->json(['success' => true]);
    }

    public function setDefault(int $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->where('id', $id)->update(['is_default' => true]);
        return response()->json(['success' => true]);
    }
}
