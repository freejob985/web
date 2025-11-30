<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function account()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $recentOrders = $user->orders()
            ->with('vendor')
            ->latest()
            ->limit(5)
            ->get();
        
        $wishlist = $user->wishlist()
            ->with('vendor')
            ->limit(10)
            ->get();

        return view('user.account', compact('user', 'addresses', 'recentOrders', 'wishlist'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female'
        ]);

        Auth::user()->update($request->only([
            'name', 'email', 'phone', 'birth_date', 'gender'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح'
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'order_updates' => 'boolean',
            'promotions' => 'boolean',
            'newsletter' => 'boolean'
        ]);

        Auth::user()->update($request->only([
            'email_notifications',
            'sms_notifications', 
            'order_updates',
            'promotions',
            'newsletter'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإعدادات بنجاح'
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'governorate' => 'required|string',
            'block' => 'nullable|string',
            'street' => 'nullable|string',
            'building' => 'nullable|string',
            'floor' => 'nullable|string',
            'apartment' => 'nullable|string',
            'phone' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        $address = Auth::user()->addresses()->create($request->all());

        if ($request->is_default) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العنوان بنجاح'
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'governorate' => 'required|string',
            'block' => 'nullable|string',
            'street' => 'nullable|string',
            'building' => 'nullable|string',
            'floor' => 'nullable|string',
            'apartment' => 'nullable|string',
            'phone' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        $address = Auth::user()->addresses()->findOrFail($id);
        $address->update($request->all());

        if ($request->is_default) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العنوان بنجاح'
        ]);
    }

    public function deleteAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        
        if ($address->is_default && Auth::user()->addresses()->count() > 1) {
            // تعيين عنوان آخر كافتراضي
            Auth::user()->addresses()
                ->where('id', '!=', $id)
                ->first()
                ->setAsDefault();
        }
        
        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العنوان بنجاح'
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        Auth::user()->addToWishlist($request->product_id);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج إلى قائمة الأمنيات'
        ]);
    }

    public function removeFromWishlist($productId)
    {
        Auth::user()->removeFromWishlist($productId);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من قائمة الأمنيات'
        ]);
    }

    public function wishlist()
    {
        $wishlist = Auth::user()->wishlist()
            ->with('vendor')
            ->paginate(12);

        return view('user.wishlist', compact('wishlist'));
    }
}
